<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\Entreprise;
use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Magic Ecommerce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Entreprises', 'fa-solid fa-gopuram', Entreprise::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-wand-sparkles', Produit::class);
        yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Paniers', 'fa-solid fa-cart-shopping', Panier::class);
        yield MenuItem::linkToCrud('Panier Produits', 'fa-solid fa-wand-sparkles', PanierProduit::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-bag-shopping', Commande::class);
        yield MenuItem::linkToCrud('Commandes détails', 'fa-solid fa-info', CommandeDetail::class);
    }
}
