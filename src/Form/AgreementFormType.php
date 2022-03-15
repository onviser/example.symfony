<?php

namespace App\Form;

use App\Entity\Agreement;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgreementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();
        $landlord = isset($data) ? $data->getLandlord() : null;

        $builder
            ->add('id', HiddenType::class)
            ->add('identificator', TextType::class, [
                'required' => true
            ])
            ->add('tenant', TextType::class, [
                'required' => true
            ])
            ->add('month_rent_amount', TextType::class, [
                'required' => true
            ])
            ->add('date_start', DateType::class, [
                'required' => true
            ])
            ->add('date_end')
            ->add('property', EntityType::class, [
                'class'         => Property::class,
                'required'      => true,
                'query_builder' => function (PropertyRepository $er) use ($landlord) {
                    return $er->createQueryBuilder('u')
                        ->where('u.landlord = :landlord')
                        ->orderBy('u.name', 'ASC')
                        ->setParameter('landlord', $landlord);
                }
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agreement::class,
        ]);
    }
}
