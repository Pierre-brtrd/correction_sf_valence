<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Model;
use App\Repository\MarqueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du modèle',
                ]
            ])
            ->add('marque', EntityType::class, [
                'label' => 'Marque',
                'class' => Marque::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une marque',
                'required' => true,
                'query_builder' => fn (MarqueRepository $repository) => $repository->createQueryBuilder('m')
                    ->andWhere('m.enable = :enable')
                    ->setParameter('enable', true)
                    ->orderBy('m.name', 'ASC'),
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
            ])
            ->add('image', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
        ]);
    }
}
