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
        // Fetch reservations for the authenticated user
        $reservations = Reservation::where('user_id', Auth::id())
                                   ->latest()
                                   ->get();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'reservation_time' => 'required|date|after:now',
            'guests' => 'required|integer|min:1|max:20',
            'contact_number' => [
                'required',
                'string',
                'regex:/^(98|97|96)\d{8}$/'
            ],
            'special_request' => 'nullable|string|max:500'
        ]);

        // Create the reservation
        Reservation::create([
            'user_id' => Auth::id(),
            'reservation_time' => $validated['reservation_time'],
            'guests' => $validated['guestes'] ?? 2,
            'contact_number' => $validated['contact_number'],
            'special_request' => $validated['special_request'] ?? null,
            'status' => 'pending'
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'रिजर्भेसन सफल भयो!');
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        // Authorize that the user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'तपाईंको अनुमति छैन');
        }

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the reservation.
     */
    public function edit(Reservation $reservation)
    {
        // Authorization check
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
            'guests' => 'required|integer|min:1|max:20',
            'contact_number' => [
                'required',
                'string',
                'regex:/^(98|97|96)\d{8}$/'
            ],
            'special_request' => 'nullable|string|max:500',
            'status' => 'in:pending,confirmed,cancelled'
        ]);

        // Update reservation
        $reservation->update($validated);

        return redirect()->route('reservations.index')
                         ->with('success', 'रिजर्भेसन अपडेट भयो!');
    }

    /**
     * Remove the specified reservation from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // Authorization
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'तपाईंको अनुमति छैन');
        }

        // Cancel or delete
        $reservation->delete();

        return redirect()->route('reservations.index')
                         ->with('success', 'रिजर्भेसन मेटाइयो!');
    }
}
