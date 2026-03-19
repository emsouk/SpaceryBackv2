<?php

namespace App\Controller\Api;

use App\Service\TypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/types', name: 'api_type_')]
final class TypeController extends AbstractController
{
    public function __construct(
        private TypeService $typeService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $types = $this->typeService->findAll();
        $data = array_map(fn($type) => $this->typeService->serialize($type), $types);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $type = $this->typeService->findById($id);

        if (!$type) {
            return $this->json(['error' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->typeService->serialize($type));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom_type'])) {
            return $this->json(['error' => 'Nom du type manquant'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $type = $this->typeService->create($data);

            return $this->json([
                'message' => 'Type créé avec succès',
                'type' => $this->typeService->serialize($type)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $type = $this->typeService->findById($id);

        if (!$type) {
            return $this->json(['error' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $type = $this->typeService->update($type, $data);

            return $this->json([
                'message' => 'Type modifié avec succès',
                'type' => $this->typeService->serialize($type)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $type = $this->typeService->findById($id);

        if (!$type) {
            return $this->json(['error' => 'Type non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->typeService->delete($type);

        return $this->json(['message' => 'Type supprimé avec succès']);
    }
}
