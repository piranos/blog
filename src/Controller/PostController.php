<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Forms\PostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @route("/admin/posts")
 */
class PostController extends AbstractController
{
    /**EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="post.index")
     * 
     * @return Response
     */
    public function index()
    {
        $posts = $this->em->getRepository(Post::class)->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="post.create")
     * @Route("/edit/{postId}", name="post.edit")
     * 
     * @param Request $request
     * @param $postId
     * 
     * @return Response
     */
    public function createPost(Request $request, $postId = null)
    {
        if ($postId) {
            $post = $this->em->getRepository(Post::class)->find($postId);
        } else {
            $post = new Post;
        }

        $form = $this->createForm(PostType::class, $post);

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

    /**
     * @Route("/remove/{postId}", name="post.remove")
     *
     * @param Request $request
     * @param int $postId
     * 
     * @return response
     */
    public function deletePost(Request $request, $postId)
    {
        $post = $this->em->getRepository(Post::class)->find($postId);
        $form = $this->createFormBuilder()
            ->add("confirm", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($post);
            $this->em->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('post/confirm_removal.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
}
