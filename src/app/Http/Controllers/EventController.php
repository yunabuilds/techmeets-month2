<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)->with('success', 'イベントを作成しました');
    }

    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        $event = Event::findOrFail($id);
        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'イベントを更新しました');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'イベントを削除しました');
    }
}