<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Event;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::latest()->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $events = Event::all();
        return view('reservations.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|max:200',
            'email' => 'required|email',
            'number_of_people' => 'required|integer',
        ]);

        $reservation = Reservation::create($validated);

        return redirect()->route('reservations.index')->with('success', '予約を作成しました');
    }

    public function show(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', '予約をキャンセルしました');
    }
}