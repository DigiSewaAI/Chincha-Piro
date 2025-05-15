<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * Display a listing of the user's reservations.
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
                                   ->latest()
                                   ->paginate(10); // ✅ Pagination Added

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the reservation form.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'reservation_time' => 'required|date|after:now',
            'guests' => 'required|integer|min:1|max:100',
            'contact_number' => [
                'required',
                'string',
                'regex:/^(98|97|96)\d{8}$/' // ✅ Nepali Mobile Format
            ],
            'special_request' => 'nullable|string|max:500'
        ], [
            'contact_number.regex' => 'सम्पर्क नम्बर 98XXXXXXXX, 97XXXXXXXX वा 96XXXXXXXX प्रारूपमा हुनुपर्छ।'
        ]);

        // Create the reservation
        Reservation::create([
            'user_id' => Auth::id(),
            'reservation_time' => $validated['reservation_time'],
            'guests' => $validated['guests'],
            'contact_number' => $validated['contact_number'],
            'special_request' => $validated['special_request'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->route('reservations.index')
                         ->with('success', 'रिजर्भेसन सफल भयो!');
    }

    /**
     * Show the form for editing the reservation.
     */
    public function edit(Reservation $reservation)
    {
        // Authorization Check
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'तपाईंको अनुमति छैन');
        }

        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Authorization
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'तपाईंको अनुमति छैन');
        }

        // Validate input
        $validated = $request->validate([
            'reservation_time' => 'required|date|after:now',
            'guests' => 'required|integer|min:1|max:100',
            'contact_number' => [
                'required',
                'string',
                'regex:/^(98|97|96)\d{8}$/'
            ],
            'special_request' => 'nullable|string|max:500'
        ]);

        // Update reservation
        $reservation->update($validated);

        return redirect()->route('reservations.index')
                         ->with('success', 'रिजर्भेसन अपडेट भयो!');
    }

    /**
     * Cancel the specified reservation.
     */
    public function destroy(Reservation $reservation)
    {
        // Authorization
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'तपाईंको अनुमति छैन');
        }

        // Cancel reservation instead of deleting it
        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('reservations.index')
                         ->with('success', 'रिजर्भेसन रद्द भयो!');
    }
}
