<?php

namespace App\Controller\Landlord;

use App\Entity\Agreement;
use App\Entity\User;
use App\Form\AgreementFormType;
use App\Repository\AgreementRepository;
use App\Repository\PropertyRepository;
use App\Service\AgreementCheck\AgreementCheckService;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AgreementController extends AbstractLandlordController
{
    /**
     * @Route("/agreement", name="app_agreement")
     */
    public function index(): Response
    {
        if (!$this->user) {
            throw $this->createAccessDeniedException();
        }
        return $this->render('agreement/index.html.twig', [
            'items' => $this->user ? $this->user->getAgreements() : []
        ]);
    }

    /**
     * @Route("/agreement/{id}", name="app_agreement_edit", requirements={"id"="\d+"})
     * @param int $id
     * @param AgreementRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(int $id, AgreementRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        $item = $repository->find($id);
        if (!$item) {
            throw $this->createNotFoundException('agreement not found');
        }
        if ($item->getLandlord()->getId() !== $this->user->getId()) {
            throw $this->createAccessDeniedException('you dont have access to this agreement');
        }
        $form = $this->createForm(AgreementFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($item);
            $manager->flush();
            return $this->redirectToRoute('app_agreement');
        }
        return $this->render('agreement/edit.html.twig', [
            'property_form' => $form->createView(),
            'item'          => $item
        ]);
    }

    /**
     * @Route("/agreement/add", name="app_agreement_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $item = (new Agreement())
            ->setLandlord($this->user)
            ->setDateStart(new DateTime())
            ->setDateEnd((new DateTime())->add(new DateInterval('P6M')));
        $form = $this->createForm(AgreementFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item->setLandlord($this->user);
            $manager->persist($item);
            $manager->flush();
            return $this->redirectToRoute('app_agreement');
        }
        return $this->render('agreement/add.html.twig', [
            'property_form' => $form->createView(),
            'item'          => $item
        ]);
    }

    /**
     * @Route("/agreement/check", name="app_agreement_check")
     * @param Request $request
     * @param PropertyRepository $repositoryProperty
     * @return JsonResponse
     */
    public function check(Request $request, PropertyRepository $repositoryProperty)
    {
        $regExpPatternDate = '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/';

        $from = $request->get('from');
        $from = preg_match($regExpPatternDate, $from) ? $from : '';
        $to = $request->get('to');
        $to = preg_match($regExpPatternDate, $to) ? $to : '';
        $agreementId = intval($request->get('agreement'));
        $propertyId = intval($request->get('property'));

        $json = [
            'request' => [
                'from'      => $from,
                'to'        => $to,
                'agreement' => $agreementId,
                'property'  => $propertyId
            ],
            'overlap' => [],
            'success' => 0,
            'error'   => ''
        ];

        try {

            if ($from === '') {
                throw new \Exception('date start is required');
            }

            // check access (i don't believe POST data)
            $property = $repositoryProperty->find($propertyId);
            if (!$property) {
                throw new \Exception('property not found');
            }
            if ($property->getLandlord()->getId() !== $this->user->getId()) {
                throw new \Exception('you dont have access to this property');
            }

            $agreementForCheck = (new Agreement())
                ->setDateStart(new DateTime($from));
            if ($agreementId > 0) {
                $agreementForCheck->setId($agreementId);
            }
            if ($to !== '') {
                $agreementForCheck->setDateEnd(new DateTime($to));
            }

            $json['overlap'] = array_map(function ($item) {
                return [
                    'id'            => $item->getId(),
                    'identificator' => $item->getIdentificator(),
                    'tenant'        => $item->getTenant(),
                    'dates'         => $item->getDateStart()->format('Y-m-d') . ' - ' . ($item->getDateEnd() ? $item->getDateEnd()->format('Y-m-d') : 'timeless')
                ];
            }, (new AgreementCheckService($property->getAgreements()->getValues()))
                ->getOverlapping($agreementForCheck));
            if (count($json['overlap']) === 0) {
                $json['success'] = 1;
            }
        }
        catch (\Exception $e) {
            $json['success'] = 0;
            $json['error'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}
