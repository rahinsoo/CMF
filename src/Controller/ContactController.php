<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

final class ContactController extends AbstractController
{
    #[Route("/contact", name: "app_contact")]
    public function index(Request $request): Response
    {
        // Créer un formulaire directement dans le contrôleur
        $form = $this->createFormBuilder()
            // Champ "nom" : texte obligatoire
            ->add("nom", TextType::class, [
                "label" => "Votre nom",
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => "Le nom est obligatoire",
                    ]),
                    new Assert\Length([
                        "min" => 2,
                        "minMessage" =>
                            "Le nom doit faire au moins 2 caractères",
                    ]),
                ],
            ])
            // Champ "email" : format email valide
            ->add("email", EmailType::class, [
                "label" => "Votre email",
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => 'L\'email est obligatoire',
                    ]),
                    new Assert\Email(["message" => "Email invalide"]),
                ],
            ])
            // Champ "message" : zone de texte
            ->add("message", TextareaType::class, [
                "label" => "Votre message",
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => "Le message est obligatoire",
                    ]),
                    new Assert\Length([
                        "min" => 10,
                        "minMessage" =>
                            "Le message doit faire au moins 10 caractères",
                    ]),
                ],
            ])
            // Bouton de soumission
            ->add("envoyer", SubmitType::class, [
                "label" => "Envoyer le message",
            ])
            ->getForm();

        // Traiter les données envoyées par le formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();

            // Ici vous pouvez traiter les données (envoyer un email, sauvegarder en BDD, etc.)
            // Pour l'exemple, on affiche juste un message de succès
            $this->addFlash(
                "success",
                "Merci " . $data["nom"] . ", votre message a été envoyé !",
            );

            // Rediriger vers la page d'accueil après succès
            return $this->redirectToRoute("app_home");
        }

        // Afficher le formulaire
        return $this->render("contact/index.html.twig", [
            "contactForm" => $form->createView(),
        ]);
    }
}
