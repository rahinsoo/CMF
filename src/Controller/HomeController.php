<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    // Route : définit l'URL qui déclenche cette méthode
    // name : identifiant unique pour générer des liens
    #[Route("/home", name: "app_home")]
    public function index(): Response
    {
        // render() : affiche un template Twig
        // Le 2ème paramètre passe des variables au template
        return $this->render("home/index.html.twig", [
            "controller_name" => "HomeController",
            "titre" => "Ma première page",
            "message" => "Bienvenue sur Symfony!",
        ]);
    }
}
