<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Show summary of income, expenses and account balances.
     */
    public function index()
    {
        $accounts = Account::with('transactions')->get();

        $totalIncome = Transaction::sum('income');
        $totalExpense = Transaction::sum('expense');
        $net = $totalIncome - $totalExpense;

        return view('expenses.index', compact('accounts', 'totalIncome', 'totalExpense', 'net'));
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


