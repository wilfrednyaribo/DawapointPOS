<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SubscriptionPayment;

class SubscriptionController extends Controller
{
    /**
     * Super Admin: List all pharmacies and their subscription status.
     */
    public function index()
    {
        // Ensure only Super Admins access this
        if (auth()->user()->pharmacy_id !== null) {
            abort(403, 'Unauthorized action.');
        }

        $pharmacies = Pharmacy::withCount('drugs')->latest()->get();

        return view('subscriptions.index', compact('pharmacies'));
    }

    /**
     * Super Admin: Show form to renew/register subscription.
     */
    public function edit(Pharmacy $pharmacy)
    {
        // Ensure only Super Admins access this
        if (auth()->user()->pharmacy_id !== null) {
            abort(403, 'Unauthorized action.');
        }

        return view('subscriptions.edit', compact('pharmacy'));
    }

    /**
     * Super Admin: Update the subscription.
     */
  public function update(Request $request, Pharmacy $pharmacy)
{
    $request->validate([
        'duration_days' => 'required|integer|min:1',
        'is_active' => 'required|boolean'
    ]);

    $startDate = ($pharmacy->subscription_ends_at && $pharmacy->subscription_ends_at->isFuture()) 
                 ? $pharmacy->subscription_ends_at 
                 : \Carbon\Carbon::today();

    $newEndDate = $startDate->addDays((int)$request->duration_days);

    // 1. Update the Pharmacy main record
    $pharmacy->subscription_ends_at = $newEndDate;
    $pharmacy->is_active = $request->is_active;
    $pharmacy->save();

    // 2. Create a Payment History Record
    SubscriptionPayment::create([
        'pharmacy_id' => $pharmacy->id,
        'days_purchased' => $request->duration_days,
        'start_date' => $pharmacy->subscription_ends_at->copy()->subDays($request->duration_days), // Approximate start
        'end_date' => $newEndDate,
        'registered_by' => auth()->id(),
    ]);

    return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
}

// Update the show method to load payments:
public function show(Pharmacy $pharmacy)
{
    if (auth()->user()->pharmacy_id !== $pharmacy->id) {
        abort(403, 'This is not your pharmacy account.');
    }

    // Eager load the payments
    $pharmacy->load('subscriptionPayments');

    return view('subscriptions.show', compact('pharmacy'));
}
}