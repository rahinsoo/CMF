<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de saisie d'un commentaire dans la zone de discussion d'un webinar.
 */
class CommentaireType extends AbstractType
{
    /**
     * Construit les champs du formulaire de commentaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('auteur', TextType::class, [
                'label' => 'Votre nom',
                'attr'  => [
                    'placeholder' => 'Entrez votre nom',
                    'class'       => 'form-input',
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre message',
                'attr'  => [
                    'placeholder' => 'Écrivez votre message ici...',
                    'class'       => 'form-textarea',
                    'rows'        => 3,
                ],
            ])
            ->add('envoyer', SubmitType::class, [
                'label' => 'Envoyer',
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
            'data_class' => Commentaire::class,
        ]);
    }
}
