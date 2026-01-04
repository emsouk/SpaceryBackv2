<?php

namespace App\Service;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class TypeService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TypeRepository $typeRepository
    ) {}

    public function findAll(): array
    {
        return $this->typeRepository->findAll();
    }

    public function findById(int $id): ?Type
    {
        return $this->typeRepository->find($id);
    }

    public function create(array $data): Type
    {
        $type = new Type();

        if (isset($data['nom_type'])) {
            $type->setNomType($data['nom_type']);
        }

        $this->em->persist($type);
        $this->em->flush();

        return $type;
    }

    public function update(Type $type, array $data): Type
    {
        if (isset($data['nom_type'])) {
            $type->setNomType($data['nom_type']);
        }

        $this->em->flush();

        return $type;
    }

    public function delete(Type $type): void
    {
        $this->em->remove($type);
        $this->em->flush();
    }

    public function serialize(Type $type): array
    {
        return [
            'id' => $type->getId(),
            'nom_type' => $type->getNomType(),
        ];
    }
}
