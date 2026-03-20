<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            AssociationField::new('type'),
            TextField::new('rue'),
            TextField::new('codePostal'),
            TextField::new('ville'),
            TextField::new('imageLieu'),
            BooleanField::new('payant'),
            TextField::new('pays'),
            NumberField::new('latLong'),
            TextareaField::new('description'),
            TextareaField::new('siteWeb'),
            TextField::new('horaires'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Lieu $entityInstance */
        if (!$entityInstance->getCreerLe()) {
            $entityInstance->setCreerLe(new \DateTime());
        }
        $entityInstance->setMajLe(new \DateTime());
        
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Lieu $entityInstance */
        $entityInstance->setMajLe(new \DateTime());
        
        parent::updateEntity($entityManager, $entityInstance);
    }
}
