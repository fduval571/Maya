<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Animal;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceRepository")
 */
class Race
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Ce libellé est trop court",
     *     maxMessage = "Ce libellé est trop long"
     * )

     */
    private $intitule;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animal", mappedBy="race")
     */
    private $animal;

    public function __construct()
    {
        $this->animal = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimal(): Collection
    {
        return $this->animal;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animal->contains($animal)) {
            $this->animal[] = $animal;
            $animal->setRace($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animal->contains($animal)) {
            $this->animal->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getRace() === $this) {
                $animal->setRace(null);
            }
        }

        return $this;
    }


}
