<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;

class FinancialRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = FinancialRecord::orderBy('name')->paginate(15);

        return view('financial_records.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial_records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        FinancialRecord::create($data);

        return redirect()
            ->route('financial-records.index')
            ->with('status', 'Financial record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialRecord $financialRecord)
    {
        return view('financial_records.show', [
            'record' => $financialRecord,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialRecord $financialRecord)
    {
        return view('financial_records.edit', [
            'record' => $financialRecord,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialRecord $financialRecord)
    {
        $data = $this->validateData($request);

        $financialRecord->update($data);

        return redirect()
            ->route('financial-records.index')
            ->with('status', 'Financial record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();

        return redirect()
            ->route('financial-records.index')
            ->with('status', 'Financial record deleted successfully.');
    }

    /**
     * Validate incoming request data.
     */
    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'initials'         => ['nullable', 'string', 'max:50'],
            'registration'     => ['nullable', 'string', 'max:255'],
            'jan'              => ['nullable', 'numeric', 'min:0'],
            'feb'              => ['nullable', 'numeric', 'min:0'],
            'mar'              => ['nullable', 'numeric', 'min:0'],
            'apr'              => ['nullable', 'numeric', 'min:0'],
            'may'              => ['nullable', 'numeric', 'min:0'],
            'jun'              => ['nullable', 'numeric', 'min:0'],
            'jul'              => ['nullable', 'numeric', 'min:0'],
            'aug'              => ['nullable', 'numeric', 'min:0'],
            'sep'              => ['nullable', 'numeric', 'min:0'],
            'oct'              => ['nullable', 'numeric', 'min:0'],
            'nov'              => ['nullable', 'numeric', 'min:0'],
            'dec'              => ['nullable', 'numeric', 'min:0'],
            'deficit'          => ['nullable', 'numeric'],
            'expected_amount'  => ['nullable', 'numeric', 'min:0'],
            'aging'            => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
