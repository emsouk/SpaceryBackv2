<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Entity\Role;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;

class UtilisateurService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UtilisateurRepository $utilisateurRepository
    ) {}

    public function findAll(): array
    {
        return $this->utilisateurRepository->findAll();
    }

    public function findById(int $id): ?Utilisateur
    {
        return $this->utilisateurRepository->find($id);
    }

    public function create(array $data): Utilisateur
    {
        $utilisateur = new Utilisateur();
        $this->hydrate($utilisateur, $data);
        $utilisateur->setDateCreationUtilisateur(new \DateTime());

        $this->em->persist($utilisateur);
        $this->em->flush();

        return $utilisateur;
    }

    public function update(Utilisateur $utilisateur, array $data): Utilisateur
    {
        $this->hydrate($utilisateur, $data);

        $this->em->flush();

        return $utilisateur;
    }

    public function delete(Utilisateur $utilisateur): void
    {
        $this->em->remove($utilisateur);
        $this->em->flush();
    }

    private function hydrate(Utilisateur $utilisateur, array $data): void
    {
        if (isset($data['nom_utilisateur'])) {
            $utilisateur->setNomUtilisateur($data['nom_utilisateur']);
        }
        if (isset($data['prenom_utilisateur'])) {
            $utilisateur->setPrenomUtilisateur($data['prenom_utilisateur']);
        }
        if (isset($data['email_utilisateur'])) {
            $utilisateur->setEmailUtilisateur($data['email_utilisateur']);
        }
        if (isset($data['status'])) {
            $utilisateur->setStatus($data['status']);
        }
        if (isset($data['image_profil'])) {
            $utilisateur->setImageProfil($data['image_profil']);
        }

        // Gestion de la relation Role
        if (isset($data['role_id'])) {
            $role = $this->em->getRepository(Role::class)->find($data['role_id']);
            if (!$role) {
                throw new \Exception('Rôle non trouvé');
            }
            $utilisateur->setRole($role);
        }
    }

    public function serialize(Utilisateur $utilisateur, bool $detailed = false): array
    {
        $data = [
            'id' => $utilisateur->getId(),
            'nom_utilisateur' => $utilisateur->getNomUtilisateur(),
            'prenom_utilisateur' => $utilisateur->getPrenomUtilisateur(),
            'email_utilisateur' => $utilisateur->getEmailUtilisateur(),
            'status' => $utilisateur->isStatus(),
        ];

        // Gestion sécurisée du rôle
        if ($utilisateur->getRole()) {
            $data['role'] = [
                'id' => $utilisateur->getRole()->getId(),
                'nom_role' => $utilisateur->getRole()->getNomRole()
            ];
        } else {
            $data['role'] = null;
        }

        if ($detailed) {
            $data['image_profil'] = $utilisateur->getImageProfil();
            $data['date_creation'] = $utilisateur->getDateCreationUtilisateur()->format('Y-m-d H:i:s');
        }

        return $data;
    }
}
