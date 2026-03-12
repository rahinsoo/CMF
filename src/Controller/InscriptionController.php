<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\MetierRepository;
use App\Repository\WebinarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant le formulaire d'inscription des participants.
 * Permet à un élève/étudiant de s'inscrire à un webinar.
 */
class InscriptionController extends AbstractController
{
    /**
     * Page "Vos Infos" : affiche et traite le formulaire d'inscription.
     * Accepte un paramètre GET optionnel "webinar" pour pré-sélectionner un webinar.
     *
     * Route : /inscription
     */
    #[Route('/inscription', name: 'app_inscription')]
    public function formulaire(
        Request $request,
        EntityManagerInterface $em,
        WebinarRepository $webinarRepository,
        MetierRepository $metierRepository
    ): Response {
        // Crée une nouvelle inscription
        $inscription = new Inscription();

        // Pré-sélectionne le webinar si l'identifiant est passé en paramètre GET
        $webinarId = $request->query->get('webinar');
        if ($webinarId) {
            $webinar = $webinarRepository->find((int) $webinarId);
            if ($webinar) {
                $inscription->setWebinar($webinar);
                $inscription->setMetierRecherche($webinar->getMetier());
            }
        }

        // Crée le formulaire d'inscription
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        // Traitement du formulaire si soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($inscription);
            $em->flush();

            $this->addFlash('success', 'Votre inscription a bien été enregistrée !');

            return $this->redirectToRoute('app_home');
        }

        // Récupère tous les métiers pour l'autocomplete du champ métier
        $tousMetiers = $metierRepository->findAllOrderedByNom();

        return $this->render('inscription/formulaire.html.twig', [
            'form'        => $form->createView(),
            'tousMetiers' => $tousMetiers,
        ]);
    }
}
