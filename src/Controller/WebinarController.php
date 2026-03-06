<?php

namespace App\Controller;

use App\Service\JitsiService;
use App\Service\WebinarStorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/webinars')]
class WebinarController extends AbstractController
{
    public function __construct(
        private WebinarStorageService $storage,
        private JitsiService          $jitsi,
    ) {}

    #[Route('', name: 'webinar_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('webinar/list.html.twig', [
            'webinars' => $this->storage->findAll(),
        ]);
    }

    #[Route('/new', name: 'webinar_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            if (empty($data['title']) || empty($data['company']) || empty($data['start_at'])) {
                $this->addFlash('error', 'Tous les champs obligatoires doivent être remplis.');
                return $this->render('webinar/new.html.twig');
            }
            $id        = bin2hex(random_bytes(6));
            $videoLink = $this->jitsi->generateLink($data['title']);

            $startAt = \DateTime::createFromFormat('Y-m-d\TH:i', $data['start_at']);
            if (!$startAt) {
                $this->addFlash('error', 'Format de date invalide.');
                return $this->render('webinar/new.html.twig');
            }

            $webinar   = [
                'id'              => $id,
                'title'           => $data['title'],
                'theme'           => $data['theme'] ?? 'Général',
                'company'         => $data['company'],
                'description'     => $data['description'] ?? '',
                'startAt'         => $startAt->format('Y-m-d H:i'),
                'videoLink'       => $videoLink,
                'maxParticipants' => 50,
                'participants'    => [],
                'createdAt'       => (new \DateTime())->format('Y-m-d H:i:s'),
            ];
            $this->storage->save($webinar);
            $this->addFlash('success', "✅ Webinaire \"{$webinar['title']}\" créé avec succès !");
            return $this->redirectToRoute('webinar_show', ['id' => $id]);
        }
        return $this->render('webinar/new.html.twig');
    }

    #[Route('/{id}', name: 'webinar_show', methods: ['GET'])]
    public function show(string $id): Response
    {
        $webinar = $this->storage->find($id);
        if (!$webinar) {
            throw $this->createNotFoundException("Webinaire introuvable.");
        }
        return $this->render('webinar/show.html.twig', [
            'webinar'          => $webinar,
            'participantCount' => count($webinar['participants']),
            'isFull'           => count($webinar['participants']) >= $webinar['maxParticipants'],
        ]);
    }

    #[Route('/{id}/join', name: 'webinar_join', methods: ['POST'])]
    public function join(string $id, Request $request): Response
    {
        $name  = $request->request->get('name', '');
        $email = $request->request->get('email', '');
        if (empty($name) || empty($email)) {
            $this->addFlash('error', 'Nom et email sont requis pour s\'inscrire.');
            return $this->redirectToRoute('webinar_show', ['id' => $id]);
        }
        $success = $this->storage->addParticipant($id, $email, $name);
        if ($success) {
            $this->addFlash('success', "🎉 Inscription confirmée ! Vous pouvez rejoindre la salle.");
        } else {
            $this->addFlash('error', "❌ Inscription impossible : salle pleine (50 max) ou déjà inscrit.");
        }
        return $this->redirectToRoute('webinar_show', ['id' => $id]);
    }
}
