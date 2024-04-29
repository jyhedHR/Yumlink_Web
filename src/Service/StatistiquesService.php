<?php


namespace App\Service;

use App\Repository\CommandeRepository;

class StatistiquesService
{
    private $commandeRepository;

    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }

    public function calculerStatistiques()
    {
        // Exemple de logique métier pour calculer les statistiques
        $totalCommandes = $this->commandeRepository->count([]);
        $montantTotal = $this->commandeRepository->calculerMontantTotal();

        return [
            'total_commandes' => $totalCommandes,
            'montant_total' => $montantTotal,
        ];
    }
}
