<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\ElectricityAccounts;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
          $st= $req->query('status', 'unpaid');
          $pp= $req->query('per_page', 20);

        $bill = Bills::when($st ,fn($q) => $q->where('status', $st))->simplePaginate($pp);

        return response()->json([
                'message' => 'Bill Success',
                'data' => $bill->items()
            ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $val = $request->validate([
            'electricity_account_id' => 'required|exists:electricity_accounts,id',
            'billing_period' => 'required|date',
            'usage_kwh' => 'required|integer',
            'status' => 'in:padi,unpaid,cancelled',
            'due_date' => 'required|date',
        ]);

        $account = ElectricityAccounts::with('service')->findOrFail($val['electricity_account_id']);

        $price = $account->service->price_per_kwh;

        $biill = Bills::create([
            ...$val,
            'price_per_kwh' => $price,
            'total_amount' => $val['usage_kwh'] * $price,
            'status' => 'unpaid'
        ]);

        return response()->json([
                'message' => 'Bill created Success',
                'data' => $biill
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $req)
    {
        $bill = Bills::wherekey($id)->when(fn($q) => $q->whereIn('electricity_account_id' , ElectricityAccounts::where('user_id', $req->user()->id)->select('id')))->first();

        if (!$bill) {
              return response()->json([
                'message' => 'Bill not Found',
            ],404);
        }

          return response()->json([
                'message' => 'Data Bill Success',
                'data' => $bill
            ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
