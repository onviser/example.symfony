<?php

namespace App\Entity;

use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 */
class Property
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="property_id")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, name="property_name")
     */
    private string $name = '';

    /**
     * @ORM\ManyToOne(targetEntity=PropertyType::class)
     * @ORM\JoinColumn(name="property_type_id", referencedColumnName="property_type_id", nullable=false)
     */
    private $propertyType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="properties")
     * @ORM\JoinColumn(name="landlord_id", referencedColumnName="id", nullable=false)
     */
    private $landlord;

    /**
     * @ORM\OneToMany(targetEntity=Agreement::class, mappedBy="property", orphanRemoval=true)
     */
    private $agreements;

    public function __construct()
    {
        $this->agreements = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \App\Entity\PropertyType|null
     */
    public function getPropertyType(): ?PropertyType
    {
        return $this->propertyType;
    }

    /**
     * @param \App\Entity\PropertyType|null $propertyType
     * @return $this
     */
    public function setPropertyType(?PropertyType $propertyType): self
    {
        $this->propertyType = $propertyType;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getLandlord(): ?User
    {
        return $this->landlord;
    }

    /**
     * @param User|null $landlord
     * @return $this
     */
    public function setLandlord(?User $landlord): self
    {
        $this->landlord = $landlord;

        return $this;
    }

    /**
     * @return Collection<int, Agreement>
     */
    public function getAgreements(): Collection
    {
        return $this->agreements;
    }

    public function addAgreement(Agreement $agreement): self
    {
        if (!$this->agreements->contains($agreement)) {
            $this->agreements[] = $agreement;
            $agreement->setProperty($this);
        }

        return $this;
    }

    public function removeAgreement(Agreement $agreement): self
    {
        if ($this->agreements->removeElement($agreement)) {
            // set the owning side to null (unless already changed)
            if ($agreement->getProperty() === $this) {
                $agreement->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return (string) $this->getName();
    }
}