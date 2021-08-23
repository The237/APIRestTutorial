<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * Le nom du repository a été rempli ici à la main car le remplissage automatique de Doctrine a créé un bug
 * @ORM\Table()
 * 
 * @ExclusionPolicy("all")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     * 
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles", cascade={"ALL"}, fetch="EAGER")
     */
    private $author;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
