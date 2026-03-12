<?php

namespace App\Controller;

use App\Form\RechercheMetierType;
use App\Repository\MetierRepository;
use App\Repository\WebinarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de la page d'accueil du site ChooseMyFutur.
 * Gère l'affichage de l'index et l'API d'autocomplete des métiers.
 */
class HomeController extends AbstractController
{
    /**
     * Page d'accueil : affiche la barre de recherche, les prochains webinars
     * et les blocs CTA pour enseignants et professionnels.
     */
    #[Route('/', name: 'app_home')]
    public function index(
        WebinarRepository $webinarRepository,
        MetierRepository $metierRepository,
        Request $request
    ): Response {
        // Récupère les prochains webinars actifs
        $prochainsWebinars = $webinarRepository->findProchainsWebinars();

        // Récupère tous les métiers pour l'autocomplete
        $tousMetiers = $metierRepository->findAllOrderedByNom();

        // Crée le formulaire de recherche
        $form = $this->createForm(RechercheMetierType::class, null, [
            'action' => $this->generateUrl('app_webinar_recherche'),
            'method' => 'GET',
        ]);

        return $this->render('home/index.html.twig', [
            'prochainsWebinars' => $prochainsWebinars,
            'tousMetiers'       => $tousMetiers,
            'form'              => $form->createView(),
        ]);
    }

    /**
     * API d'autocomplete : retourne une liste de métiers correspondant
     * au terme de recherche passé en paramètre GET.
     *
     * Route : /api/metiers/autocomplete?q=terme
     */
    #[Route('/api/metiers/autocomplete', name: 'app_api_metiers_autocomplete')]
    public function autocomplete(Request $request, MetierRepository $metierRepository): JsonResponse
    {
        // Récupère le terme de recherche depuis la requête
        $terme = $request->query->get('q', '');

        if (strlen($terme) < 2) {
            return $this->json([]);
        }

        // Recherche les métiers correspondants
        $metiers = $metierRepository->findByNomLike($terme);

        // Formate les résultats en tableau JSON
        $resultats = array_map(fn($m) => [
            'id'  => $m->getId(),
            'nom' => $m->getNom(),
        ], $metiers);

        return $this->json($resultats);
    }
}
