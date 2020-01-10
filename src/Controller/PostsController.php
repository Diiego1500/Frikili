<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use App\Form\ComentarioType;
use App\Form\PostsType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class PostsController extends AbstractController
{
    /**
     * @Route("/registrar-posts", name="RegistrarPosts")
     */
    public function index(Request $request)
    {
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $File = $form['foto']->getData();
            if ($File) {
                $originalFilename = pathinfo($File->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$File->guessExtension();
                try {
                    $File->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   throw new \Exception('UPs! ha ocurrido un error, sorry :c');
                }
                $post->setFoto($newFilename);
            }
            $user = $this->getUser();
            $post->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('posts/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}", name="VerPost")
     */
    public function VerPost($id, Request $request, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        $comentario = new Comentarios();
        $post = $em->getRepository(Posts::class)->find($id);
        $queryComentarios = $em->getRepository(Comentarios::class)->BuscarComentariosDeUNPost($post->getId());
        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $comentario->setPosts($post);
            $comentario->setUser($user);
            $em->persist($comentario);
            $em->flush();
            $this->addFlash('Exito', Comentarios::COMENTARIO_AGREGADO_EXITOSAMENTE);
            return $this->redirectToRoute('VerPost',['id'=>$post->getId()]);
        }
        $pagination = $paginator->paginate(
            $queryComentarios, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('posts/verPost.html.twig',['post'=>$post, 'form'=>$form->createView(), 'comentarios'=>$pagination]);
    }

    /**
     * @Route("/mis-posts", name="MisPosts")
     */
    public function MisPost(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user'=>$user]);
        return $this->render('posts/MisPosts.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("/Likes", options={"expose"=true}, name="Likes")
     */
    public function Like(Request $request){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $id = $request->request->get('id');
            $post = $em->getRepository(Posts::class)->find($id);
            $likes = $post->getLikes();
            $likes .= $user->getId().',';
            $post->setLikes($likes);
            $em->flush();
            return new JsonResponse(['likes'=>$likes]);
        }else{
            throw new \Exception('Estás tratando de hackearme?');
        }
    }
}
