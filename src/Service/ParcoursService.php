<?php

namespace App\Service;

use App\Entity\Parcours;
use App\Entity\Utilisateur;
use App\Repository\ParcoursRepository;
use Doctrine\ORM\EntityManagerInterface;

class ParcoursService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ParcoursRepository $parcoursRepository
    ) {}

    public function findAll(): array
    {
        return $this->parcoursRepository->findAll();
    }

    public function findById(int $id): ?Parcours
    {
        return $this->parcoursRepository->find($id);
    }

    public function create(array $data): Parcours
    {
        $parcours = new Parcours();
        $this->hydrate($parcours, $data);
        $parcours->setDateCreation(new \DateTime());

        $this->em->persist($parcours);
        $this->em->flush();

        return $parcours;
    }

    public function update(Parcours $parcours, array $data): Parcours
    {
        $this->hydrate($parcours, $data);

        $this->em->flush();

        return $parcours;
    }

    public function delete(Parcours $parcours): void
    {
        $this->em->remove($parcours);
        $this->em->flush();
    }

    private function hydrate(Parcours $parcours, array $data): void
    {
        if (isset($data['titre'])) {
            $parcours->setTitre($data['titre']);
        }
        if (isset($data['description_parcours'])) {
            $parcours->setDescriptionParcours($data['description_parcours']);
        }
        if (isset($data['image_parcours'])) {
            $parcours->setImageParcours($data['image_parcours']);
        }
        if (isset($data['duree_estime'])) {
            $parcours->setDureeEstime($data['duree_estime']);
        }

        // Gestion de la relation Utilisateur
        if (isset($data['utilisateur_id'])) {
            $utilisateur = $this->em->getRepository(Utilisateur::class)->find($data['utilisateur_id']);
            if (!$utilisateur) {
                throw new \Exception('Utilisateur non trouvé');
            }
            $parcours->setUtilisateur($utilisateur);
        }
    }

    public function serialize(Parcours $parcours, bool $detailed = false): array
    {
        $data = [
            'id' => $parcours->getId(),
            'titre' => $parcours->getTitre(),
            'description_parcours' => $parcours->getDescriptionParcours(),
            'duree_estime' => $parcours->getDureeEstime(),
        ];

        // Gestion sécurisée de l'utilisateur
        if ($parcours->getUtilisateur()) {
            $data['utilisateur'] = [
                'id' => $parcours->getUtilisateur()->getId(),
                'nom' => $parcours->getUtilisateur()->getNomUtilisateur(),
                'prenom' => $parcours->getUtilisateur()->getPrenomUtilisateur()
            ];
        } else {
            $data['utilisateur'] = null;
        }

        if ($detailed) {
            $data['image_parcours'] = $parcours->getImageParcours();
            $data['date_creation'] = $parcours->getDateCreation()->format('Y-m-d H:i:s');

            // Ajouter les lieux du parcours (relation ManyToMany)
            $lieux = [];
            foreach ($parcours->getLieux() as $lieu) {
                $lieux[] = [
                    'id' => $lieu->getId(),
                    'nom' => $lieu->getNom(),
                    'ville' => $lieu->getVille()
                ];
            }
            $data['lieux'] = $lieux;
        }

        return $data;
    }
}
