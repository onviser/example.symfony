<?php

namespace App\Controller\Landlord;

use App\Entity\Property;
use App\Entity\User;
use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;
use App\Repository\PropertyTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractLandlordController
{
    /**
     * @Route("/property", name="app_property")
     * @return Response
     */
    public function index(): Response
    {
        if (!$this->user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('property/index.html.twig', [
            'items' => $this->user ? $this->user->getProperties() : []
        ]);
    }

    /**
     * @Route("/property/{id}", name="app_property_edit", requirements={"id"="\d+"})
     * @param int $id
     * @param PropertyRepository $propertyRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(int $id, PropertyRepository $propertyRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $item = $propertyRepository->find($id);
        if (!$item) {
            throw $this->createNotFoundException('property not found');
        }
        if ($item->getLandlord()->getId() !== $this->user->getId()) {
            throw $this->createAccessDeniedException('you dont have access to this property');
        }
        $form = $this->createForm(PropertyFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($item);
            $manager->flush();
            return $this->redirectToRoute('app_property');
        }
        return $this->render('property/edit.html.twig', [
            'property_form' => $form->createView(),
            'item'          => $item
        ]);
    }

    /**
     * @Route("/property/add", name="app_property_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $item = new Property();
        $form = $this->createForm(PropertyFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item->setLandlord($this->user);
            $manager->persist($item);
            $manager->flush();
            return $this->redirectToRoute('app_property');
        }
        return $this->render('property/add.html.twig', [
            'property_form' => $form->createView(),
            'item'          => $item
        ]);
    }
}
