<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'attr' => ['class' => 'form__input'],
            ])
            ->add('preparationTime', null, [
                'label' => 'Temps de préparation (minutes)',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('restingTime', null, [
                'label' => 'Temps de repos (minutes)',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('cookingTime', null, [
                'label' => 'Temps de cuisson (minutes)',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('image', null, [
                'label' => 'Image de la recette',
                'label_attr' => ['class' => 'form__label'],
                'attr' => ['class' => 'form__input'],
            ])
            ->add('allergens', EntityType::class, [
                'class' => Allergen::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form__choices'],
                'label' => 'Allergènes',
                'label_attr' => ['class' => 'form__label'],
            ])
            ->add('diets', EntityType::class, [
                'class' => Diet::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form__choices'],
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