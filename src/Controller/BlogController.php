<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Forms\ReactionType;

/**
 * 
 */
class BlogController extends AbstractController
{
    /**EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/blog", name="blog.index")
     */
    public function index()
    {
        $posts = $this->em->getRepository(Post::class)->findAll();

        return $this->render('blog/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{postId}", name="blog.post")
     */
    public function showPost(Request $request, $postId)
    {
        $post = $this->em->getRepository(Post::class)->find($postId);

        $form = $this->createForm(reactionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reaction = $form->getData();

            $this->em->persist($reaction);
            $post->addReaction($reaction);

            $this->em->flush();
        }

        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reaction/new", name="reaction.create")
     */
    public function createReaction(Request $request, $postId = null)
    {
        if ($postId) {
            $post = $this->em->getRepository(Post::class)->find($postId);
        } else {
            $post = new Post;
        }

        $form = $this->createForm(reactionType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('post.index');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
