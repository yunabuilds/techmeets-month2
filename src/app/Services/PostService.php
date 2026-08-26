<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    public function __construct(
        private PostRepository $postRepository
    ) {}

    public function createPost(array $data)
    {
        return $this->postRepository->create($data);
    }

    public function updatePost(string $id, array $data)
    {
        $post = $this->postRepository->findById($id);
        return $this->postRepository->update($post, $data);
    }

    public function deletePost(string $id)
    {
        $post = $this->postRepository->findById($id);
        return $this->postRepository->delete($post);
    }
}