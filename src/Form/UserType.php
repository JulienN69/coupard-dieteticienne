<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\User;
use App\Entity\Allergen;
use App\Repository\DietRepository;
use App\Repository\AllergenRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    private $allergenRepository;
    private $dietRepository;


    public function __construct(AllergenRepository $allergenRepository, DietRepository $dietRepository)
    {
        $this->allergenRepository = $allergenRepository;
        $this->dietRepository = $dietRepository;
    }


public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $allergens = $this->allergenRepository->findAll();
    $diets = $this->dietRepository->findAll();

    $allergenChoices = [];
        foreach ($allergens as $allergen) {
            $allergenChoices[$allergen->getName()] = $allergen->getId();
        }

    $dietChoices = [];
        foreach ($diets as $diet) {
            $dietChoices[$diet->getName()] = $diet->getId();
        }

    $builder
        ->add('email', EmailType::class, [
            'attr' => ['class' => 'form__input'],
            'label_attr' => ['class' => 'form__label'],
        ])
        ->add('password', PasswordType::class, [
            'attr' => ['class' => 'form__input'],
            'label_attr' => ['class' => 'form__label'],
        ])
        ->add('firstName', TextType::class, [
            'attr' => ['class' => 'form__input'],
            'label_attr' => ['class' => 'form__label'],
            'label'=>'Prénom'
        ])
        ->add('lastName', TextType::class, [
            'attr' => ['class' => 'form__input'],
            'label_attr' => ['class' => 'form__label'],
            'label'=>'Nom'
        ])
        // ->add('allergy', ChoiceType::class, [
        //         'choices' => $allergenChoices, // Utilisation des allergènes récupérés
        //         'expanded' => true,
        //         'multiple' => true, // Permet de sélectionner plusieurs allergènes
        //         'attr' => ['class' => 'form__choices'],
        //         'label_attr' => ['class' => 'form__label'],
        //         'label'=>'allergies'
        //     ])
        ->add('allergy', EntityType::class, [
            'class' => Allergen::class,
            'choice_label' => 'name',
            'expanded' => true,   // Affiche des cases à cocher (checkbox)
            'multiple' => true,   // Permet de sélectionner plusieurs valeurs
            'attr' => ['class' => 'form__choices'],
            'label_attr' => ['class' => 'form__label'],
        ])

        // ->add('diets', ChoiceType::class, [
        //         'choices' => $dietChoices, // Utilisation des allergènes récupérés
        //         'expanded' => true,
        //         'multiple' => true, // Permet de sélectionner plusieurs allergènes
        //         'attr' => ['class' => 'form__choices'],
        //         'label_attr' => ['class' => 'form__label'],
        //         'label'=>'régime(s) alimentaire'
        //     ]);
                ->add('diets', EntityType::class, [
            'class' => Diet::class,
            'choice_label' => 'name',
            'expanded' => true,   // Affiche des cases à cocher (checkbox)
            'multiple' => true,   // Permet de sélectionner plusieurs valeurs
            'attr' => ['class' => 'form__choices'],
            'label_attr' => ['class' => 'form__label'],
                ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
