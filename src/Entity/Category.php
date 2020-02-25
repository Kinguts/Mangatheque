<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Serie", mappedBy="category")
     */
    private $series;

    public function __construct()
    {
        $this->series = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }


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

    /**
     * @return Collection|Serie[]
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    public function addSerie(Serie $serie): self
    {
        if (!$this->series->contains($serie)) {
            $this->series[] = $serie;
            $serie->setCategory($this);
        }

        return $this;
    }

    public function removeSerie(Serie $serie): self
    {
        if ($this->series->contains($serie)) {
            $this->series->removeElement($serie);
            // set the owning side to null (unless already changed)
            if ($serie->getCategory() === $this) {
                $serie->setCategory(null);
            }
        }

        return $this;
    }
}
