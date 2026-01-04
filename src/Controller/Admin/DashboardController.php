<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Parcours;
use App\Entity\Role;
use App\Entity\Type;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/spaceryAdmin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Spacery - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Gestion des lieux');
        yield MenuItem::linkToCrud('Types', 'fas fa-list', Type::class);
        yield MenuItem::linkToCrud('Lieux', 'fas fa-map-marker-alt', Lieu::class);

        yield MenuItem::section('Gestion des parcours');
        yield MenuItem::linkToCrud('Parcours', 'fas fa-route', Parcours::class);

        yield MenuItem::section('Gestion des utilisateurs');
        yield MenuItem::linkToCrud('Rôles', 'fas fa-user-shield', Role::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Utilisateur::class);
    }
}
