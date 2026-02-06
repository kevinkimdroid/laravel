<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Contribution;
use App\Models\Member;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Show summary of income, expenses and account balances.
     */
    public function index(Request $request)
    {
        $accounts = Account::with('transactions')->get();

        $monthlyContributions = Contribution::where('type', 'monthly_contribution')->sum('amount');
        $rawRegistrationInjection = Contribution::where('type', 'registration_fee')->sum('amount');
        $totalExpense = Transaction::sum('expense');
        $operatingIncome = $monthlyContributions;
        $net = $operatingIncome - $totalExpense;
        $openingBalance = (float) $request->input('opening_balance', 0);
        $expenseBudget = (float) $request->input('expense_budget', 0);
        $overuseAmount = max(0, $totalExpense - $expenseBudget);
        $overusePercent = $expenseBudget > 0 ? round(($totalExpense / $expenseBudget) * 100, 2) : null;
        $netCashPosition = $openingBalance + $net;
        $expectedRegistration = Member::count() * 1000;
        $registrationInjection = min($rawRegistrationInjection, $expectedRegistration);
        $registrationVariance = $expectedRegistration - $registrationInjection;

        return view('expenses.index', compact(
            'accounts',
            'monthlyContributions',
            'registrationInjection',
            'totalExpense',
            'net',
            'openingBalance',
            'expenseBudget',
            'overuseAmount',
            'overusePercent',
            'netCashPosition',
            'expectedRegistration',
            'registrationVariance'
        ));
    }

    /**
     * Download profit & loss report as CSV.
     */
    public function export(Request $request)
    {
        $accounts = Account::with('transactions')->get();
        $monthlyContributions = Contribution::where('type', 'monthly_contribution')->sum('amount');
        $rawRegistrationInjection = Contribution::where('type', 'registration_fee')->sum('amount');
        $totalExpense = Transaction::sum('expense');
        $operatingIncome = $monthlyContributions;
        $net = $operatingIncome - $totalExpense;
        $openingBalance = (float) $request->input('opening_balance', 0);
        $expenseBudget = (float) $request->input('expense_budget', 0);
        $overuseAmount = max(0, $totalExpense - $expenseBudget);
        $overusePercent = $expenseBudget > 0 ? round(($totalExpense / $expenseBudget) * 100, 2) : null;
        $netCashPosition = $openingBalance + $net;
        $expectedRegistration = Member::count() * 1000;
        $registrationInjection = min($rawRegistrationInjection, $expectedRegistration);
        $registrationVariance = $expectedRegistration - $registrationInjection;

        $rows = [
            ['QBASH Profit & Loss Report'],
            ['Generated At', now()->format('Y-m-d H:i:s')],
            [],
            ['P&L Statement'],
            ['Line Item', 'Amount (KES)'],
            ['Opening Balance', number_format($openingBalance, 2)],
            ['Operating Income - Monthly Contributions', number_format($monthlyContributions, 2)],
            ['Total Expenses', number_format($totalExpense, 2)],
            ['Net Profit / (Loss)', number_format($net, 2)],
            ['Capital Injection - Registration', number_format($registrationInjection, 2)],
            ['Expected Registration', number_format($expectedRegistration, 2)],
            ['Registration Variance', number_format($registrationVariance, 2)],
            ['Net Cash Position', number_format($netCashPosition, 2)],
            ['Expense Budget', number_format($expenseBudget, 2)],
            ['Overuse Amount', number_format($overuseAmount, 2)],
            ['Overuse %', $overusePercent === null ? 'N/A' : $overusePercent . '%'],
            ['Note', 'Registration injection is reported separately and not included in net cash position.'],
            [],
            ['Accounts Snapshot'],
            ['#', 'Name', 'Balance (KES)'],
        ];

        foreach ($accounts as $index => $account) {
            $rows[] = [
                $index + 1,
                $account->name,
                number_format($account->balance, 2),
            ];
        }

        $filename = 'profit-loss-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Show form to record a new expense.
     */
    public function create()
    {
        $accounts = Account::all();

        // Ensure there is at least one account
        if ($accounts->isEmpty()) {
            $accounts[] = Account::create([
                'name' => 'General Account',
                'slug' => 'general',
                'balance' => 0,
            ]);
        }

        return view('expenses.create', ['accounts' => $accounts]);
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        $account = Account::findOrFail($validated['account_id']);

        Transaction::create([
            'account_id' => $account->id,
            'income' => 0,
            'expense' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
        ]);

        $account->decrement('balance', $validated['amount']);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }
}


