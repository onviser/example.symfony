<?php

namespace App\Entity;

use App\Repository\PropertyTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyTypeRepository::class)
 */
class PropertyType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="property_type_id")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, name="property_type_name")
     */
    private ?string $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return string|null
     */
    public function __toString(): ?string
    {
        return (string) $this->getName();
    }
}
