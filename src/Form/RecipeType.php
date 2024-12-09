<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\Recipe;
use App\Entity\Allergen;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
            ->add('thumbnailFile', VichFileType::class, [
                'label' => 'Image de la recette',
                'allow_delete' => true,
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
