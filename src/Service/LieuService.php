<?php

namespace App\Service;

use App\Entity\Lieu;
use App\Entity\Type;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class LieuService
{


    public function __construct(private SerializerInterface $serializer, private EntityManagerInterface $em,  private LieuRepository $lieuRepository)
    {

    }

    public function findAll(): array
    {
        return $this->lieuRepository->findAll();
    }

    public function findById(int $id): ?Lieu
    {
        return $this->lieuRepository->find($id);
    }

    public function create(array $data): Lieu
{
    // Désérialisation de base
    $lieu = $this->serializer->deserialize(
        json_encode($data),
        Lieu::class,
        'json',
        [AbstractNormalizer::IGNORED_ATTRIBUTES => ['type', 'id', 'creerLe', 'majLe']]
    );

    // Gestion manuelle des champs spéciaux
    $lieu->setCreerLe(new \DateTime());
    $lieu->setMajLe(new \DateTime());

    // Gestion de la relation Type
    if (isset($data['type_id'])) {
        $type = $this->em->getRepository(Type::class)->find($data['type_id']);
        if (!$type) {
            throw new \Exception('Type non trouvé');
        }
        $lieu->setType($type);
    }

    $this->em->persist($lieu);
    $this->em->flush();

    return $lieu;
}

    public function update(Lieu $lieu, array $data): Lieu
    {
        $this->hydrate($lieu, $data);
        $lieu->setMajLe(new \DateTime());

        $this->em->flush();

        return $lieu;
    }

    public function delete(Lieu $lieu): void
    {
        $this->em->remove($lieu);
        $this->em->flush();
    }

    private function hydrate(Lieu $lieu, array $data): void
    {
        if (isset($data['nom'])) $lieu->setNom($data['nom']);
        if (isset($data['rue'])) $lieu->setRue($data['rue']);
        if (isset($data['code_postal'])) $lieu->setCodePostal($data['code_postal']);
        if (isset($data['ville'])) $lieu->setVille($data['ville']);
        if (isset($data['pays'])) $lieu->setPays($data['pays']);
        if (isset($data['payant'])) $lieu->setPayant($data['payant']);
        if (isset($data['description'])) $lieu->setDescription($data['description']);
        if (isset($data['image_lieu'])) $lieu->setImageLieu($data['image_lieu']);
        if (isset($data['site_web'])) $lieu->setSiteWeb($data['site_web']);
        if (isset($data['horaires'])) $lieu->setHoraires($data['horaires']);
        if (isset($data['lat_long'])) $lieu->setLatLong($data['lat_long']);

        // Gestion du type
        if (isset($data['type_id'])) {
            $type = $this->em->getRepository(Type::class)->find($data['type_id']);
            if ($type) {
                $lieu->setType($type);
            }
        }
    }

public function serialize(Lieu $lieu, bool $detailed = false): array
{
    $data = [
        'id' => $lieu->getId(),
        'nom' => $lieu->getNom(),
        'rue' => $lieu->getRue(),
        'code_postal' => $lieu->getCodePostal(),
        'ville' => $lieu->getVille(),
        'pays' => $lieu->getPays(),
        'payant' => $lieu->isPayant(),
        'image_lieu' => $lieu->getImageLieu() // ✅ toujours incluse
    ];

    // Gestion du type
    if ($lieu->getType()) {
        $data['type'] = [
            'id' => $lieu->getType()->getId(),
            'nom' => $lieu->getType()->getNomType()
        ];
    } else {
        $data['type'] = null;
    }

    if ($detailed) {
        $data['description'] = $lieu->getDescription();
        $data['site_web'] = $lieu->getSiteWeb();
        $data['horaires'] = $lieu->getHoraires();
        $data['creer_le'] = $lieu->getCreerLe()->format('Y-m-d H:i:s');
        $data['maj_le'] = $lieu->getMajLe()->format('Y-m-d H:i:s');
    }

    return $data;
}
}
