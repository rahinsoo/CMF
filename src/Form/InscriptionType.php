<?php

namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire d'inscription d'un élève/étudiant à un webinar.
 * Permet de saisir les informations personnelles du participant.
 */
class InscriptionType extends AbstractType
{
    /**
     * Construit les champs du formulaire d'inscription.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr'  => [
                    'placeholder' => 'Votre nom de famille',
                    'class'       => 'form-input',
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr'  => [
                    'placeholder' => 'Votre prénom',
                    'class'       => 'form-input',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr'  => [
                    'placeholder' => 'votre@email.fr',
                    'class'       => 'form-input',
                ],
            ])
            ->add('ecole', TextType::class, [
                'label'    => 'École / Établissement',
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Nom de votre établissement',
                    'class'       => 'form-input',
                ],
            ])
            ->add('region', ChoiceType::class, [
                'label'    => 'Région',
                'required' => false,
                'placeholder' => '-- Sélectionnez votre région --',
                'choices' => [
                    'Auvergne-Rhône-Alpes'     => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté'  => 'Bourgogne-Franche-Comté',
                    'Bretagne'                 => 'Bretagne',
                    'Centre-Val de Loire'      => 'Centre-Val de Loire',
                    'Corse'                    => 'Corse',
                    'Grand Est'                => 'Grand Est',
                    'Hauts-de-France'          => 'Hauts-de-France',
                    'Île-de-France'            => 'Île-de-France',
                    'Normandie'                => 'Normandie',
                    'Nouvelle-Aquitaine'       => 'Nouvelle-Aquitaine',
                    'Occitanie'                => 'Occitanie',
                    'Pays de la Loire'         => 'Pays de la Loire',
                    "Provence-Alpes-Côte d'Azur" => "Provence-Alpes-Côte d'Azur",
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('metierRecherche', TextType::class, [
                'label'    => 'Métier qui vous intéresse',
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Ex: Infirmier, Architecte, Développeur...',
                    'class'       => 'form-input',
                    'list'        => 'metiers-list',
                ],
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr'  => ['class' => 'btn-primary'],
            ])
        ;
    }

    /**
     * Configure les options du formulaire (entité associée).
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
