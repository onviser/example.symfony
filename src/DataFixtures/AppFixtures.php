<?php

namespace App\DataFixtures;

use App\Entity\Agreement;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AppFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasherInterface;

    /**
     * AppFixtures constructor.
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $amountLandLords = 2;
        $amountTenants = 2;
        $amountPropertyMin = 1;
        $amountPropertyMax = 4;
        $password = 'pass';

        $propertyTypeHouse = new PropertyType();
        $propertyTypeHouse->setName('house');
        $manager->persist($propertyTypeHouse);

        $propertyTypeApartment = new PropertyType();
        $propertyTypeApartment->setName('apartment');
        $manager->persist($propertyTypeApartment);

        for ($i = 1; $i <= $amountLandLords; $i++) {
            $landLord = new User();
            $landLord->setEmail('landlord' . $i . '@example.com');
            $landLord->setRoles(['ROLE_LANDLORD']);
            $landLord->setPassword($this->userPasswordHasherInterface->hashPassword($landLord, $password));
            $manager->persist($landLord);

            // set random property
            for ($j = 1; $j <= rand($amountPropertyMin, $amountPropertyMax); $j++) {
                $property = new Property();
                $property->setName('Property Name ' . $i . '/' . $j);
                $property->setPropertyType($j % 2 ? $propertyTypeHouse : $propertyTypeApartment);
                $property->setLandlord($landLord);
                $manager->persist($property);

                $month = rand(6, 12);
                $dateStart = new DateTimeImmutable();
                $dateEnd = $dateStart->add(new \DateInterval('P' . $month . 'M'));
                $agreement = new Agreement();
                $agreement->setLandlord($landLord);
                $agreement->setProperty($property);
                $agreement->setIdentificator("agreement-{$i}-{$j}");
                $agreement->setTenant('John Dow');
                $agreement->setMonthRentAmount($month);
                $agreement->setDateStart($dateStart);
                $agreement->setDateEnd($dateEnd);
                $manager->persist($agreement);
            }
        }

        for ($i = 1; $i <= $amountTenants; $i++) {
            $tenants = new User();
            $tenants->setEmail('tenant' . $i . '@example.com');
            $tenants->setRoles(['ROLE_TENANT']);
            $tenants->setPassword($this->userPasswordHasherInterface->hashPassword($tenants, $password));
            $manager->persist($tenants);
        }

        $manager->flush();
    }
}
