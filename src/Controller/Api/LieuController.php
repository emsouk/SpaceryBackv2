<?php

namespace App\Controller\Api;

use App\Service\LieuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/lieux', name: 'api_lieu_')]
final class LieuController extends AbstractController
{
    public function __construct(
        private LieuService $lieuService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $lieux = $this->lieuService->findAll();
        $data = array_map(fn($lieu) => $this->lieuService->serialize($lieu), $lieux);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $lieu = $this->lieuService->findById($id);

        if (!$lieu) {
            return $this->json(['error' => 'Lieu non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->lieuService->serialize($lieu, true));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom']) || !isset($data['type_id'])) {
            return $this->json(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $lieu = $this->lieuService->create($data);

            return $this->json([
                'message' => 'Lieu créé avec succès',
                'lieu' => $this->lieuService->serialize($lieu, true)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $lieu = $this->lieuService->findById($id);

        if (!$lieu) {
            return $this->json(['error' => 'Lieu non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $lieu = $this->lieuService->update($lieu, $data);

            return $this->json([
                'message' => 'Lieu modifié avec succès',
                'lieu' => $this->lieuService->serialize($lieu, true)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $lieu = $this->lieuService->findById($id);

        if (!$lieu) {
            return $this->json(['error' => 'Lieu non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->lieuService->delete($lieu);

        return $this->json(['message' => 'Lieu supprimé avec succès']);
    }
}
