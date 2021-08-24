<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * Le nom du repository a été rempli ici à la main car le remplissage automatique de Doctrine a créé un bug
 * @ORM\Table()
 * 
 * @ExclusionPolicy("all")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_article_show",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "modify",
 *      href = @Hateoas\Route(
 *          "app_article_update",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_article_update",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "author",
 *      embedded = @Hateoas\Embedded("expr(object.getAuthor())")
 * )
 * 
 * @Hateoas\Relation(
 *      "weather",
 *      embedded = @Hateoas\Embedded("expr(service('app.weather').getCurrent())")
 * )
 * 
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     * 
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles", cascade={"ALL"}, fetch="EAGER")
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Since("2.0")
     * @Expose
     */
    private $shortDescription;

    
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
