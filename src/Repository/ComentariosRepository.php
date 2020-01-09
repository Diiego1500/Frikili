<?php

namespace App\Repository;

use App\Entity\Comentarios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comentarios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comentarios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comentarios[]    findAll()
 * @method Comentarios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentariosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comentarios::class);
    }

    public function BuscarComentarios($id_user){
        return $this->getEntityManager()
            ->createQuery('
                SELECT comentario.id, post.titulo, post.id
                FROM App:Comentarios comentario
                JOIN comentario.posts post
                WHERE comentario.user =:user_id
            ')
            ->setParameter('user_id',$id_user)
            ->setMaxResults(10)
            ->getResult();
    }

    public function BuscarComentariosDeUNPost($post_id){
        return $this->getEntityManager()
            ->createQuery('
                SELECT comentario.comentario, user.nombre
                FROM App:Comentarios comentario
                JOIN comentario.user user
                WHERE comentario.posts =:post_id
            ')
            ->setParameter('post_id',$post_id);
    }

    // /**
    //  * @return Comentarios[] Returns an array of Comentarios objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comentarios
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
