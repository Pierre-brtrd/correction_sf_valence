<?php

namespace App\Form;

use App\Entity\Gender;
use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Product;
use App\Repository\GenderRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModelRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du produit',
                ]
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'name',
                'placeholder' => "Sélectionner une marque",
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
                'query_builder' => function (MarqueRepository $repo): QueryBuilder {
                    return $repo->createQueryBuilder('ma')
                        ->andWhere('ma.enable = true')
                        ->orderBy('ma.name', 'ASC');
                }
            ])
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'choice_label' => 'name',
                'placeholder' => "Sélectionner un model",
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
                'query_builder' => function (ModelRepository $repo): QueryBuilder {
                    return $repo->createQueryBuilder('mo')
                        ->andWhere('mo.enable = true')
                        ->orderBy('mo.name', 'ASC');
                }
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'placeholder' => "Sélectionner un genre",
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
                'query_builder' => function (GenderRepository $repo): QueryBuilder {
                    return $repo->createQueryBuilder('g')
                        ->andWhere('g.enable = true')
                        ->orderBy('g.name', 'ASC');
                }
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du produit',
                    'rows' => 5,
                ]
            ])
            ->add('authencity', TextareaType::class, [
                'label' => 'Auth',
                'attr' => [
                    'rows' => 3,
                ],
                'required' => false,
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
