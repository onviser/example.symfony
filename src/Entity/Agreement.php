<?php

namespace App\Entity;

use App\Repository\AgreementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgreementRepository::class)
 */
class Agreement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="agreement_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, name="agreement_identificator")
     */
    private $identificator;

    /**
     * @ORM\ManyToOne(targetEntity=Property::class, inversedBy="agreements")
     * @ORM\JoinColumn(nullable=false, name="property_id", referencedColumnName="property_id")
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="agreements")
     * @ORM\JoinColumn(nullable=false, name="landlord_id", referencedColumnName="id")
     */
    private $landlord;

    /**
     * @ORM\Column(type="string", length=255, name="agreement_tenant")
     */
    private $tenant;

    /**
     * @ORM\Column(type="integer", name="agreement_month_rent_amount")
     */
    private $month_rent_amount;

    /**
     * @ORM\Column(type="date", name="agreement_date_start")
     */
    private $date_start;

    /**
     * @ORM\Column(type="date", nullable=true, name="agreement_date_end")
     */
    private $date_end;

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

    public function getIdentificator(): ?string
    {
        return $this->identificator;
    }

    public function setIdentificator(string $identificator): self
    {
        $this->identificator = $identificator;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getTenant(): ?string
    {
        return $this->tenant;
    }

    public function setTenant(string $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getMonthRentAmount(): ?int
    {
        return $this->month_rent_amount;
    }

    public function setMonthRentAmount(int $month_rent_amount): self
    {
        $this->month_rent_amount = $month_rent_amount;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getLandlord(): ?User
    {
        return $this->landlord;
    }

    public function setLandlord(?User $landlord): self
    {
        $this->landlord = $landlord;

        return $this;
    }
}
