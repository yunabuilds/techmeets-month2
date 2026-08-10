<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class BoardPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $boardPosts = BoardPost::latest()->paginate(10);
        return view('board.index', compact('boardPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('board.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|max:1000',
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['author_name'] = auth()->user()->name;
        } else {
            $validated['author_name'] = '名無しさん';
        }

        BoardPost::create($validated);

        return redirect()->route('board.index')->with('success', '投稿しました');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $boardPost = BoardPost::findOrFail($id);
        return view('board.show', compact('boardPost'));
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
       $boardPost = BoardPost::findOrFail($id);

        if (!auth()->check() || $boardPost->user_id !== auth()->id()) {
            abort(403, 'この操作は許可されていません');
        }

        $boardPost->delete();

        return redirect()->route('board.index')->with('success', '投稿を削除しました'); 
    }
}
