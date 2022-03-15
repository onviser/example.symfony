<?php

namespace App\Controller\Landlord;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

abstract class AbstractLandlordController extends AbstractController
{
    /** @var User|null */
    protected ?User $user;

    /**
     * PropertyController constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }
}