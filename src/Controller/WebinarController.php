<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Form\RechercheMetierType;
use App\Repository\MetierRepository;
use App\Repository\WebinarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant les pages liées aux webinars :
 * liste, détail/visualisation, et recherche par métier.
 */
class WebinarController extends AbstractController
{
    /**
     * Page liste des webinars : affiche tous les webinars actifs.
     *
     * Route : /webinars
     */
    #[Route('/webinars', name: 'app_webinar_liste')]
    public function liste(WebinarRepository $webinarRepository): Response
    {
        // Récupère tous les webinars actifs
        $webinars = $webinarRepository->findTousActifs();

        return $this->render('webinar/liste.html.twig', [
            'webinars' => $webinars,
        ]);
    }

    /**
     * Page de visualisation d'un webinar : affiche le lecteur vidéo
     * et la zone de discussion avec le formulaire de commentaire.
     *
     * Route : /webinars/{id}
     */
    #[Route('/webinars/{id}', name: 'app_webinar_voir', requirements: ['id' => '\d+'])]
    public function voir(
        int $id,
        WebinarRepository $webinarRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        // Cherche le webinar par son identifiant
        $webinar = $webinarRepository->find($id);

        if (!$webinar) {
            throw $this->createNotFoundException('Webinar introuvable.');
        }

        // Crée un nouveau commentaire et son formulaire
        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);

        // Traitement du formulaire si soumis et valide
        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $commentaire->setWebinar($webinar);
            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé !');

            return $this->redirectToRoute('app_webinar_voir', ['id' => $id]);
        }

        return $this->render('webinar/voir.html.twig', [
            'webinar'         => $webinar,
            'formCommentaire' => $formCommentaire->createView(),
        ]);
    }

    /**
     * Page de recherche par métier : affiche les webinars correspondant
     * au terme saisi dans la barre de recherche.
     *
     * Route : /recherche
     */
    #[Route('/recherche', name: 'app_webinar_recherche')]
    public function recherche(
        Request $request,
        WebinarRepository $webinarRepository,
        MetierRepository $metierRepository
    ): Response {
        // Crée le formulaire de recherche
        $form = $this->createForm(RechercheMetierType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        $resultats = [];
        $terme = '';

        // Effectue la recherche si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            $terme = $form->get('terme')->getData() ?? '';
            if ($terme !== '') {
                $resultats = $webinarRepository->findByMetier($terme);
            }
        }

        // Récupère tous les métiers pour l'autocomplete
        $tousMetiers = $metierRepository->findAllOrderedByNom();

        return $this->render('webinar/recherche.html.twig', [
            'form'        => $form->createView(),
            'resultats'   => $resultats,
            'terme'       => $terme,
            'tousMetiers' => $tousMetiers,
        ]);
    }
}
