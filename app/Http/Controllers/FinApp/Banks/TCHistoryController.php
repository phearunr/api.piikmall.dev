<?php

namespace App\Http\Controllers\FinApp\Banks;

use Illuminate\Http\Request;
use App\Models\Banks\TCHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Banks\TCHistoryImport;
use App\Models\Banks\TCHistoryDetails;
use App\Http\Resources\Banks\TCHistoryResource;
use App\Http\Resources\Banks\TCHistoryDetailsResource;

class TCHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TCHistory::query()->with('author')->orderBy('id', 'DESC')->paginate(10);
        return  TCHistoryResource::Collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'printed_on' => 'required',
            'branch' => 'required',
            'statement_period_start' => 'required',
            'statement_period_end' => 'required',
            'open_date' => 'required',
            'current_available_balance' => 'required',
            'current_ledger_balance' => 'required',
        ]);

        return DB::transaction(function () use ($request) { // Start the transaction

            return TCHistory::create([
                "printed_on" => $request->printed_on,
                "branch" => $request->branch,
                "statement_period_start" => $request->statement_period_start,
                "statement_period_end" => $request->statement_period_end,
                "currency" => $request->currency ?? 'USD',
                "open_date" => $request->open_date,
                "account_number" => $request->account_number,
                "current_available_balance" => $request->current_available_balance,
                "current_ledger_balance" => $request->current_ledger_balance,
                "customer_info" => $request->customer_info ?? [],
                "author_id" => auth()->id()
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  new TCHistoryResource(TCHistory::query()->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'printed_on' => 'required',
            'branch' => 'required',
            'statement_period_start' => 'required',
            'statement_period_end' => 'required',
            'open_date' => 'required',
            'current_available_balance' => 'required',
            'current_ledger_balance' => 'required',
        ]);

        $TCHistory = TCHistory::query()->findOrFail($id);
        return DB::transaction(function () use ($TCHistory, $request) { // Start the transaction
            return $TCHistory->update([
                "printed_on" => $request->printed_on,
                "branch" => $request->branch,
                "statement_period_start" => $request->statement_period_start,
                "statement_period_end" => $request->statement_period_end,
                "currency" => $request->currency ?? 'USD',
                "open_date" => $request->open_date,
                "account_number" => $request->account_number,
                "current_available_balance" => $request->current_available_balance,
                "current_ledger_balance" => $request->current_ledger_balance,
                "customer_info" => $request->customer_info ?? []
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $TCHistory = TCHistory::query()->findOrFail($id);
        return DB::transaction(function () use ($TCHistory) {
            return $TCHistory->delete();
        });
    }

    public function import_file(Request $request, $id)
    {
        $request->validate([
            'tc_history_file' => 'required',
            'tc_history_id' => 'required|unique:tc_history_details',
        ]);

        $excel = Excel::import(
            new TCHistoryImport,
            $request->file('tc_history_file')
        );

        if ($excel) {
            return response([
                'error' => 0,
                'message' => 'successed.'
            ], 201);
        } else {
            return response([
                'error' => 1,
                'message' => 'failed.'
            ], 202);
        }
    }

    public function items_detail($id)
    {
        return  new TCHistoryDetailsResource(
            TCHistoryDetails::query()->where(['tc_history_id' => $id])->paginate(10)
        );
    }
}
