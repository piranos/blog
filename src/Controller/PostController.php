<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post.index")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PostController.php',
        ]);
    }

    /**
     * @Route("/post/new", name="post.new")
     * @Route("/post/edit/{id}", name="post.create")
     */
    public function createPost(Post $post = null)
    {
        $form = new 
    }
}
