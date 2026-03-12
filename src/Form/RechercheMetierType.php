<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de recherche de métier avec autocomplete.
 * Utilisé sur la page d'accueil et la page de recherche.
 */
class RechercheMetierType extends AbstractType
{
    /**
     * Construit les champs du formulaire de recherche.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('terme', SearchType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Cherchez parmis plus de 200 métiers (auto completion)',
                    'class'       => 'search-input',
                    'list'        => 'metiers-list',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Rechercher',
                'attr'  => ['class' => 'btn-search'],
            ])
        ;
    }

    /**
     * Configure les options du formulaire de recherche.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Pas de data_class, ce formulaire n'est pas lié à une entité
            'csrf_protection' => false,
            'method'          => 'GET',
        ]);
    }
}
