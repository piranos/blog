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
     * @Route("/", name="blog.index")
     * 
     * @return Response
     */
    public function index()
    {
        $posts = $this->em->getRepository(Post::class)->findAll();

        return $this->render('blog/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{post}", name="blog.post")
     *
     * @param Request $request
     * @param Post $postId
     * 
     * @return Response
     */
    public function showPost(Request $request, Post $post)
    {
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
}
