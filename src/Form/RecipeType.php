<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de la recette',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__textarea'],
            ])
            ->add('preparationTime', null, [
                'label' => 'préparation',
                'label_attr' => ['class' => 'form__label-time'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('restingTime', null, [
                'label' => 'repos',
                'label_attr' => ['class' => 'form__label-time'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('cookingTime', null, [
                'label' => 'cuisson',
                'label_attr' => ['class' => 'form__label-time'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Image de la recette',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
                'required' => false, // Si l'image n'est pas obligatoire
            ])
            ->add('allergens', EntityType::class, [
                'class' => Allergen::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'checkbox-item'],
                'label' => 'Allergènes',
                'label_attr' => ['class' => 'form__label'],
            ])
            ->add('diets', EntityType::class, [
                'class' => Diet::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'checkbox-item'],
                'label' => 'Régimes',
                'label_attr' => ['class' => 'form__label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
