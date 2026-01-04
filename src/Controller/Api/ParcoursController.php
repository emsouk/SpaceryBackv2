<?php

namespace App\Controller\Api;

use App\Service\ParcoursService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/parcours', name: 'api_parcours_')]
final class ParcoursController extends AbstractController
{
    public function __construct(
        private ParcoursService $parcoursService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $parcours = $this->parcoursService->findAll();
        $data = array_map(fn($p) => $this->parcoursService->serialize($p), $parcours);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $parcours = $this->parcoursService->findById($id);

        if (!$parcours) {
            return $this->json(['error' => 'Parcours non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->parcoursService->serialize($parcours, true));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre']) || !isset($data['utilisateur_id'])) {
            return $this->json(['error' => 'Données manquantes (titre, utilisateur_id)'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $parcours = $this->parcoursService->create($data);

            return $this->json([
                'message' => 'Parcours créé avec succès',
                'parcours' => $this->parcoursService->serialize($parcours, true)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $parcours = $this->parcoursService->findById($id);

        if (!$parcours) {
            return $this->json(['error' => 'Parcours non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $parcours = $this->parcoursService->update($parcours, $data);

            return $this->json([
                'message' => 'Parcours modifié avec succès',
                'parcours' => $this->parcoursService->serialize($parcours, true)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $parcours = $this->parcoursService->findById($id);

        if (!$parcours) {
            return $this->json(['error' => 'Parcours non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->parcoursService->delete($parcours);

        return $this->json(['message' => 'Parcours supprimé avec succès']);
    }
}
