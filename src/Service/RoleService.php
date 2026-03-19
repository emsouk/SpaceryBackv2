<?php

namespace App\Service;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RoleRepository $roleRepository
    ) {}

    public function findAll(): array
    {
        return $this->roleRepository->findAll();
    }

    public function findById(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    public function create(array $data): Role
    {
        $role = new Role();

        if (isset($data['nom_role'])) {
            $role->setNomRole($data['nom_role']);
        }

        $this->em->persist($role);
        $this->em->flush();

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        if (isset($data['nom_role'])) {
            $role->setNomRole($data['nom_role']);
        }

        $this->em->flush();

        return $role;
    }

    public function delete(Role $role): void
    {
        $this->em->remove($role);
        $this->em->flush();
    }

    public function serialize(Role $role): array
    {
        return [
            'id' => $role->getId(),
            'nom_role' => $role->getNomRole(),
        ];
    }
}
