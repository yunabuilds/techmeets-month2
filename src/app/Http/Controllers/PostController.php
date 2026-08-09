<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:200',
        'content' => 'required',
        'category' => 'required',
    ]);
    $validated['user_id'] = auth()->id(); 
    $post = Post::create($validated);

    return redirect()->route('posts.show', $post)->with('success', '投稿を作成しました');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $post = Post::findOrFail($id);
    return view('posts.show', compact('post'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $post = Post::findOrFail($id);

    if ($post->user_id !== auth()->id()) {
        abort(403, 'この操作は許可されていません');
    }

    return view('posts.edit', compact('post'));
}

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'title' => 'required|max:200',
        'content' => 'required',
        'category' => 'required',
    ]);

    $post = Post::findOrFail($id);

    if ($post->user_id !== auth()->id()) {
        abort(403, 'この操作は許可されていません');
    }

    $post->update($validated);

    return redirect()->route('posts.show', $post)->with('success', '投稿を更新しました');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $post = Post::findOrFail($id);

    if ($post->user_id !== auth()->id()) {
        abort(403, 'この操作は許可されていません');
    }

    $post->delete();

    return redirect()->route('posts.index')->with('success', '投稿を削除しました');
}
}
