<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComentariosRepository")
 */
class Comentarios
{
    const COMENTARIO_AGREGADO_EXITOSAMENTE = '¡Comentario Agregado exitosamente!';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8000)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comentarios")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Posts", inversedBy="comentarios")
     */
    private $posts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts): void
    {
        $this->posts = $posts;
    }

}
