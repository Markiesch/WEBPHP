<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalReturnController extends Controller
{
    public function index()
    {
        $activeRentals = AdvertisementTransaction::with(['advertisement.business'])
            ->where('user_id', Auth::id())
            ->where('type', 'rental')
            ->whereNull('return_date')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                $rentalDays = $transaction->rental_days ?? 7;
                $endDate = $transaction->created_at->addDays($rentalDays);
                $isOverdue = now()->startOfDay()->isAfter($endDate);

                return (object)[
                    'transaction' => $transaction,
                    'advertisement' => $transaction->advertisement,
                    'start_date' => $transaction->created_at,
                    'end_date' => $endDate,
                    'is_overdue' => $isOverdue,
                    'days_remaining' => (int)now()->startOfDay()->diffInDays($endDate, false)
                ];
            });

        return view('public.rental-return', [
            'activeRentals' => $activeRentals
        ]);
    }

    public function return(Request $request, AdvertisementTransaction $transaction)
    {
        $request->validate([
            'return_photo' => 'required|image|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($transaction->return_date) {
            return back()->with('error', __('This product has already been returned.'));
        }

        // Calculate wear based on days rented and wear per day
        $daysRented = now()->diffInDays($transaction->created_at);
        $wearPerDay = $transaction->advertisement->wear_per_day ?? 0;
        $calculatedWear = $daysRented * $wearPerDay;

        // Store photo
        $photoPath = $request->file('return_photo')->store('return-photos', 'public');

        // Update transaction using markAsReturned method
        $transaction->markAsReturned([
            'return_date' => now(),
            'return_photo' => $photoPath,
            'calculated_wear' => $calculatedWear
        ]);

        // Update additional fields
        $transaction->update([
            'return_notes' => $request->notes
        ]);

        return redirect()->route('rental-return.index')
            ->with('success', __('Product successfully returned.'));
    }

    public function create(AdvertisementTransaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        abort_if($transaction->return_date !== null, 404);
        abort_if($transaction->type !== 'rental', 404);

        return view('public.rental-return-create', [
            'transaction' => $transaction->load('advertisement.business')
        ]);
    }
}
