<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Forms\ReactionType;

/**
 * @route("/admin")
 */
class AdminController extends AbstractController
{
    /**EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/", name="admin.index")
     * 
     * @return Response
     */
    public function index()
    {
        $posts = $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('admin/index.html.twig', [
            'post_count' => $posts,
        ]);
    }
}
