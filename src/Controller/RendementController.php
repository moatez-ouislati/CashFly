<?php

namespace App\Controller;

use App\Entity\RendementInvestissement;
use App\Entity\Investissement;
use App\Repository\RendementInvestissementRepository;
use App\Repository\InvestissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rendements')]
class RendementController extends AbstractController
{
    #[Route('/create', name: 'app_rendements_create', methods: ['POST'])]
    public function create(
        InvestissementRepository $investissementRepository,
        RendementInvestissementRepository $rendementRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $idInvestissement = $_POST['id_investissement'] ?? null;
        $dateCalcul = $_POST['date_calcul'] ?? null;
        $gain = $_POST['gain'] ?? 0;
        $perte = $_POST['perte'] ?? 0;
        $valeurPortefeuille = $_POST['valeur_portefeuille'] ?? 0;

        if (!$idInvestissement || !$dateCalcul) {
            $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
            return $this->redirectToRoute('app_rendements');
        }

        $investissement = $investissementRepository->find($idInvestissement);
        if (!$investissement) {
            $this->addFlash('error', 'Investissement non trouve.');
            return $this->redirectToRoute('app_rendements');
        }

        $rendement = new RendementInvestissement();
        $rendement->setInvestissement($investissement);
        $rendement->setDateCalcul(new \DateTime($dateCalcul));
        $rendement->setGain($gain);
        $rendement->setPerte($perte);
        $rendement->setValeurPortefeuille($valeurPortefeuille);

        $entityManager->persist($rendement);
        $entityManager->flush();

        $this->addFlash('success', 'Rendement ajoute avec succes!');
        return $this->redirectToRoute('app_rendements');
    }

    #[Route('/update/{id}', name: 'app_rendements_update', methods: ['POST'])]
    public function update(
        int $id,
        InvestissementRepository $investissementRepository,
        RendementInvestissementRepository $rendementRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $rendement = $rendementRepository->find($id);
        
        if (!$rendement) {
            $this->addFlash('error', 'Rendement non trouve.');
            return $this->redirectToRoute('app_rendements');
        }

        $idInvestissement = $_POST['id_investissement'] ?? null;
        $dateCalcul = $_POST['date_calcul'] ?? null;
        $gain = $_POST['gain'] ?? 0;
        $perte = $_POST['perte'] ?? 0;
        $valeurPortefeuille = $_POST['valeur_portefeuille'] ?? 0;

        if ($idInvestissement) {
            $investissement = $investissementRepository->find($idInvestissement);
            if ($investissement) {
                $rendement->setInvestissement($investissement);
            }
        }

        if ($dateCalcul) {
            $rendement->setDateCalcul(new \DateTime($dateCalcul));
        }

        $rendement->setGain($gain);
        $rendement->setPerte($perte);
        $rendement->setValeurPortefeuille($valeurPortefeuille);

        $entityManager->flush();

        $this->addFlash('success', 'Rendement modifie avec succes!');
        return $this->redirectToRoute('app_rendements');
    }

    #[Route('/delete/{id}', name: 'app_rendements_delete', methods: ['POST'])]
    public function delete(
        int $id,
        RendementInvestissementRepository $rendementRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $rendement = $rendementRepository->find($id);
        
        if (!$rendement) {
            $this->addFlash('error', 'Rendement non trouve.');
            return $this->redirectToRoute('app_rendements');
        }

        $entityManager->remove($rendement);
        $entityManager->flush();

        $this->addFlash('success', 'Rendement supprime avec succes!');
        return $this->redirectToRoute('app_rendements');
    }
}
