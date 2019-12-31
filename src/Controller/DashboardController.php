<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Posts::class)->findAll();
        $post = $em->getRepository(Posts::class)->findBy(['likes'=>'']);
        return $this->render('dashboard/index.html.twig', [
            'posts' => $posts,
            'post'=>$post
        ]);
    }
}
