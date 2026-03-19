<?php

namespace App\Controller\Api;

use App\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/roles', name: 'api_role_')]
final class RoleController extends AbstractController
{
    public function __construct(
        private RoleService $roleService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $roles = $this->roleService->findAll();
        $data = array_map(fn($role) => $this->roleService->serialize($role), $roles);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->json(['error' => 'Rôle non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->roleService->serialize($role));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom_role'])) {
            return $this->json(['error' => 'Nom du rôle manquant'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $role = $this->roleService->create($data);

            return $this->json([
                'message' => 'Rôle créé avec succès',
                'role' => $this->roleService->serialize($role)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->json(['error' => 'Rôle non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $role = $this->roleService->update($role, $data);

            return $this->json([
                'message' => 'Rôle modifié avec succès',
                'role' => $this->roleService->serialize($role)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);

        if (!$role) {
            return $this->json(['error' => 'Rôle non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->roleService->delete($role);

        return $this->json(['message' => 'Rôle supprimé avec succès']);
    }
}
