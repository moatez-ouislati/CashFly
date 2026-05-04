<?php

namespace App\Controller;

use App\Service\AiInvestmentAdvisor;
use App\Service\InvestmentService;
use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Form\InvestissementType;
use App\Entity\Investissement;
use App\Entity\Entreprise;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/investisseur')]
#[IsGranted('ROLE_INVESTISSEUR')]
class InvestisseurController extends AbstractController
{
    private InvestmentService $investmentService;
    private AiInvestmentAdvisor $aiAdvisor;

    public function __construct(InvestmentService $investmentService, AiInvestmentAdvisor $aiAdvisor)
    {
        $this->investmentService = $investmentService;
        $this->aiAdvisor = $aiAdvisor;
    }

    #[Route('/investment/new', name: 'investisseur_investment_new')]
    public function newInvestment(Request $request, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        $entrepriseId = $request->query->get('entrepriseId');
        $investissement = new Investissement();
        
        if ($entrepriseId) {
            $entreprise = $em->getRepository(Entreprise::class)->find($entrepriseId);
            if ($entreprise) {
                $investissement->setEntreprise($entreprise);
            }
        }

        $form = $this->createForm(InvestissementType::class, $investissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $investissement->setInvestisseur($this->getUser());
            $investissement->setStatut('EN_ATTENTE');
            $investissement->setDateInvestissement(new \DateTime());

            $em->persist($investissement);
            $em->flush();

            $this->addFlash('success', 'Votre demande d\'investissement a été envoyée avec succès.');
            return $this->redirectToRoute('investisseur_investments');
        }

        return $this->render('investisseur/new_investment.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/investment/{id}/edit', name: 'investisseur_investment_edit', methods: ['GET', 'POST'])]
    public function editInvestment(Request $request, Investissement $investissement, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        if ($investissement->getInvestisseur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cet investissement.');
        }

        if ($investissement->getStatut() !== 'EN_ATTENTE') {
            $this->addFlash('error', 'Vous ne pouvez plus modifier un investissement qui a été validé ou refusé.');
            return $this->redirectToRoute('investisseur_investments');
        }

        // We save the enterprise to ensure it's not lost if the form somehow omits it
        $originalEntreprise = $investissement->getEntreprise();

        $form = $this->createForm(InvestissementType::class, $investissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Force the original enterprise back if it was nulled (security and data integrity)
            if ($investissement->getEntreprise() === null && $originalEntreprise !== null) {
                $investissement->setEntreprise($originalEntreprise);
            }
            
            $em->flush();
            $this->addFlash('success', 'Votre investissement a été modifié avec succès.');
            return $this->redirectToRoute('investisseur_investments');
        }

        return $this->render('investisseur/edit_investment.html.twig', [
            'form' => $form,
            'investissement' => $investissement,
        ]);
    }

    #[Route('/investment/{id}/delete', name: 'investisseur_investment_delete', methods: ['POST'])]
    public function deleteInvestment(Request $request, Investissement $investissement, EntityManagerInterface $em): Response
    {
        if ($investissement->getInvestisseur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cet investissement.');
        }

        if ($investissement->getStatut() !== 'EN_ATTENTE') {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer un investissement déjà traité.');
            return $this->redirectToRoute('investisseur_investments');
        }

        if ($this->isCsrfTokenValid('delete'.$investissement->getId(), $request->request->get('_token'))) {
            $em->remove($investissement);
            $em->flush();
            $this->addFlash('success', 'Investissement supprimé avec succès.');
        }

        return $this->redirectToRoute('investisseur_investments');
    }

    #[Route('/dashboard', name: 'investisseur_dashboard')]
    public function dashboard(): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        /** @var User $user */
        $user = $this->getUser();
        $stats = $this->investmentService->getPortfolioStats($user);

        return $this->render('investisseur/dashboard.html.twig', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    #[Route('/investments', name: 'investisseur_investments')]
    public function investments(): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        /** @var User $user */
        $user = $this->getUser();
        $stats = $this->investmentService->getPortfolioStats($user);
        
        return $this->render('investisseur/investments.html.twig', [
            'stats' => $stats,
            'user' => $user,
        ]);
    }

    #[Route('/ai-advisor', name: 'investisseur_ai_advisor')]
    public function aiAdvisor(Request $request): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        /** @var User $user */
        $user = $this->getUser();
        
        return $this->render('investisseur/ai_advisor.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/ai-advisor/analyze', name: 'investisseur_ai_advisor_analyze', methods: ['POST'])]
    public function aiAnalyze(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $userProfile = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'experience' => $user->getYearsExperience(),
            'budget' => $user->getBudget()
        ];
        
        try {
            $portfolio = $this->investmentService->getPortfolioStats($user);
            $refresh = $request->query->get('refresh') === '1';
            $recommendations = $this->aiAdvisor->getRecommendations($userProfile, $portfolio, $refresh);
            return $this->json($recommendations);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Désolé, une erreur est survenue lors de l\'analyse : ' . $e->getMessage(),
            ]);
        }
    }

    #[Route('/explore', name: 'investisseur_explore')]
    public function explore(Request $request, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        $search = $request->query->get('q');
        $qb = $em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.roles = :role')
            ->setParameter('role', 'proprietaire')
            ->setMaxResults(20);

        if ($search) {
            $qb->andWhere('u.nom LIKE :q OR u.prenom LIKE :q')
                ->setParameter('q', '%' . $search . '%');
        }

        $users = $qb->getQuery()->getResult();

        if ($request->isXmlHttpRequest()) {
            $data = array_map(fn($u) => [
                'id' => $u->getId(),
                'nom' => $u->getNom(),
                'prenom' => $u->getPrenom(),
                'fullName' => $u->getFullName(),
                'tel' => $u->getTel(),
                'cin' => $u->getCin(),
                'faceImage' => $u->getFaceImage(),
            ], $users);

            return $this->json($data);
        }

        return $this->render('investisseur/explore.html.twig', ['users' => $users]);
    }

    #[Route('/profile', name: 'investisseur_profile')]
    public function profile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $mustCompleteProfile = $user->needsInvestorProfileCompletion();

        $form = $this->createForm(ProfileFormType::class, $user, [
            'show_extra_fields' => true,
            'require_investor_completion' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isBlank($user->getYearsExperience()) || $this->isBlank($user->getBudget())) {
                $this->addFlash('error', "Les champs années d'expérience et budget sont obligatoires.");

                return $this->render('investisseur/profile.html.twig', [
                    'form' => $form,
                    'user' => $user,
                    'mustCompleteProfile' => $mustCompleteProfile,
                ]);
            }

            $imageFile = $form->get('faceImageFile')->getData();
            if ($imageFile) {
                $safeFilename = $slugger->slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($this->getParameter('face_images_directory'), $newFilename);
                    $user->setFaceImage($newFilename);
                } catch (FileException) {
                    $this->addFlash('warning', "Erreur lors de l'upload.");
                }
            }

            $em->flush();

            $imagePath = $this->getParameter('face_images_directory') . '/' . $user->getFaceImage();

            $email = (new Email())
                ->from('system@cashfly.tn')
                ->to('salma.talbi@esprit.tn')
                ->subject('Modification Profil avec Photo : Investisseur')
                ->html("
                    <h3>Alerte Modification de Profil</h3>
                    <p>L'investisseur <b>{$user->getFullName()}</b> a mis à jour ses données.</p>
                    <ul>
                        <li><b>CIN :</b> {$user->getCin()}</li>
                        <li><b>Email :</b> {$user->getEmail()}</li>
                    </ul>
                    <p>Tu trouveras sa photo en pièce jointe.</p>
                ");

            if ($user->getFaceImage() && file_exists($imagePath)) {
                $email->attachFromPath($imagePath);
            }

            $mailer->send($email);

            $this->addFlash('success', 'Profil mis à jour et Admin notifié.');

            if ($mustCompleteProfile) {
                return $this->redirectToRoute('investisseur_dashboard');
            }

            return $this->redirectToRoute('investisseur_profile');
        }

        return $this->render('investisseur/profile.html.twig', [
            'form' => $form,
            'user' => $user,
            'mustCompleteProfile' => $mustCompleteProfile,
        ]);
    }

    #[Route('/investment/{id}/pdf', name: 'investisseur_investment_pdf')]
    public function generatePdf(Investissement $investissement): Response
    {
        // Check if the investment belongs to the user or if user is admin
        if ($investissement->getInvestisseur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('investisseur/investment_pdf.html.twig', [
            'inv' => $investissement,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'recu_investissement_' . $investissement->getId() . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function redirectToProfileCompletion(): ?Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($user instanceof User && $user->needsInvestorProfileCompletion()) {
            $this->addFlash('warning', "Veuillez compléter votre expérience et votre budget avant de continuer.");

            return $this->redirectToRoute('investisseur_profile', ['complete' => 1]);
        }

        return null;
    }

    private function isBlank(?string $value): bool
    {
        return $value === null || trim($value) === '';
    }
}
