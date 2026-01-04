<?php

namespace App\Controller\Api;

use App\Service\UtilisateurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/utilisateurs', name: 'api_utilisateur_')]
final class UtilisateurController extends AbstractController
{
    public function __construct(
        private UtilisateurService $utilisateurService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $utilisateurs = $this->utilisateurService->findAll();
        $data = array_map(fn($utilisateur) => $this->utilisateurService->serialize($utilisateur), $utilisateurs);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $utilisateur = $this->utilisateurService->findById($id);

        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->utilisateurService->serialize($utilisateur, true));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom_utilisateur']) || !isset($data['email_utilisateur']) || !isset($data['role_id'])) {
            return $this->json(['error' => 'Données manquantes (nom, email, role_id)'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $utilisateur = $this->utilisateurService->create($data);

            return $this->json([
                'message' => 'Utilisateur créé avec succès',
                'utilisateur' => $this->utilisateurService->serialize($utilisateur, true)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $utilisateur = $this->utilisateurService->findById($id);

        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $utilisateur = $this->utilisateurService->update($utilisateur, $data);

            return $this->json([
                'message' => 'Utilisateur modifié avec succès',
                'utilisateur' => $this->utilisateurService->serialize($utilisateur, true)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $utilisateur = $this->utilisateurService->findById($id);

        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->utilisateurService->delete($utilisateur);

        return $this->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
