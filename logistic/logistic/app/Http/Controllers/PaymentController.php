<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        if ($shipment->customer_id !== $request->user()->id) {
            abort(403);
        }

        if ($shipment->status !== 'confirmed' || $shipment->shipping_fee === null) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment is not ready for payment.',
            ], 409);
        }

        $paymentExists = $shipment->payments()
            ->whereIn('status', ['pending', 'completed'])
            ->exists();

        if ($paymentExists) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already exists.',
            ], 409);
        }

        $data = $request->validate([
            'payment_method' => 'required|string|max:50',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $payment = $shipment->payments()->create([
            'amount' => $shipment->shipping_fee,
            'payment_method' => $data['payment_method'],
            'payment_proof' => $request->file('payment_proof')?->store('payment-proofs', 'local'),
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment submitted successfully.',
            'data' => $payment,
        ], 201);
    }

    public function verify(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is already processed.',
            ], 409);
        }

        $data = $request->validate([
            'status' => 'required|in:completed,rejected',
        ]);

        $payment->update([
            'status' => $data['status'],
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'data' => $payment->fresh(),
        ]);
    }
}
