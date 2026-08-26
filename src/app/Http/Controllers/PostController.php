<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Repositories\PostRepository;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService,
        private PostRepository $postRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $posts = $this->postRepository->getAll();
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

    $post = $this->postService->createPost($validated);

    return redirect()->route('posts.show', $post)->with('success', '投稿を作成しました');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
   $post = $this->postRepository->findById($id);
    return view('posts.show', compact('post'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $post = $this->postRepository->findById($id);
    return view('posts.edit', compact('post'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $post = $this->postRepository->findById($id);
    $this->authorize('update', $post); 


    $validated = $request->validate([
        'title' => 'required|max:200',
        'content' => 'required',
        'category' => 'required',
    ]);

    $post = $this->postService->updatePost($id, $validated);

    return redirect()->route('posts.show', $post)->with('success', '投稿を更新しました');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $post = $this->postRepository->findById($id);
    $this->authorize('delete', $post);

    $this->postService->deletePost($id);

    return redirect()->route('posts.index')->with('success', '投稿を削除しました');
}
}
