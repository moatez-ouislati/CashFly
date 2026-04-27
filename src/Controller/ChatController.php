<?php

namespace App\Controller;

use App\Entity\ChatMessage;
use App\Entity\ChatPresence;
use App\Entity\JourneePorteOuverte;
use App\Entity\Utilisateur;
use App\Repository\ChatMessageRepository;
use App\Repository\ChatPresenceRepository;
use App\Repository\JourneePorteOuverteRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/chat')]
class ChatController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChatMessageRepository $chatMessageRepository,
        private ChatPresenceRepository $chatPresenceRepository,
        private JourneePorteOuverteRepository $jpoRepository,
        private UtilisateurRepository $utilisateurRepository
    ) {}

    private function canAccessChat(JourneePorteOuverte $event, Utilisateur $user): bool
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($user->getId() === $event->getIdCreateur()) {
            return true;
        }

        $registrationIds = $this->jpoRepository->findUserRegistrationIds($user->getId());
        return in_array($event->getIdEvenement(), $registrationIds, true);
    }

    #[Route('/{eventId}/messages', name: 'chat_load_messages', methods: ['GET'])]
    public function loadMessages(int $eventId): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 401);
        }

        $event = $this->jpoRepository->find($eventId);
        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        if (!$this->canAccessChat($event, $user)) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $now = new \DateTime();
        $eventStart = $event->getDateEvenement();
        $isReadOnly = $now > (clone $eventStart)->modify('+12 hours');

        $messages = $this->chatMessageRepository->findByRoom($eventId);
        $data = [];

        foreach ($messages as $msg) {
            $author = $this->utilisateurRepository->find($msg->getIdUtilisateur());
            $isAdmin = in_array('ROLE_ADMIN', $author->getRoles(), true);
            $authorData = $author ? [
                'id' => $author->getId(),
                'nom' => $isAdmin ? 'Administrateur' : $author->getNomComplet(),
                'role' => $author->getRoles()[0] ?? 'ROLE_USER',
                'is_owner' => $author->getId() === $event->getIdCreateur(),
                'is_admin' => $isAdmin
            ] : null;

            $replyData = null;
            if ($msg->getReplyTo()) {
                $parent = $this->chatMessageRepository->find($msg->getReplyTo());
                if ($parent) {
                    $parentAuthor = $this->utilisateurRepository->find($parent->getIdUtilisateur());
                    $replyData = [
                        'author' => $parentAuthor ? $parentAuthor->getNomComplet() : 'Inconnu',
                        'snippet' => mb_strimwidth($parent->getMessage(), 0, 50, '...')
                    ];
                }
            }

            $data[] = [
                'id' => $msg->getIdMessage(),
                'message' => $msg->getStatus() === 'deleted' ? 'Message supprimé' : $msg->getMessage(),
                'author' => $authorData,
                'created_at' => $msg->getCreatedAt()->format('c'),
                'is_edited' => $msg->isEdited(),
                'status' => $msg->getStatus(),
                'reply_to' => $replyData,
                'can_edit' => !$isReadOnly && $user->getId() === $msg->getIdUtilisateur(),
                'can_delete' => $this->isGranted('ROLE_ADMIN') || (!$isReadOnly && $user->getId() === $msg->getIdUtilisateur())
            ];
        }

        return new JsonResponse([
            'messages' => $data,
            'is_readonly' => $isReadOnly
        ]);
    }

    #[Route('/{eventId}/send', name: 'chat_send_message', methods: ['POST'])]
    public function sendMessage(int $eventId, Request $request): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 401);
        }

        $event = $this->jpoRepository->find($eventId);
        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        if (!$this->canAccessChat($event, $user)) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $deadline = (clone $event->getDateEvenement())->modify('+12 hours');
        if (new \DateTime() > $deadline) {
            return new JsonResponse(['error' => 'Le chat est désormais en lecture seule'], 403);
        }

        $content = json_decode($request->getContent(), true);
        $messageText = trim($content['message'] ?? '');
        $replyTo = $content['reply_to'] ?? null;

        if (empty($messageText)) {
            return new JsonResponse(['error' => 'Le message ne peut pas être vide'], 400);
        }

        $msg = new ChatMessage();
        $msg->setIdRoom($eventId);
        $msg->setIdUtilisateur($user->getId());
        $msg->setMessage($messageText);
        $msg->setReplyTo($replyTo);

        $this->entityManager->persist($msg);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'id' => $msg->getIdMessage()]);
    }

    #[Route('/edit/{id}', name: 'chat_edit_message', methods: ['POST'])]
    public function editMessage(int $id, Request $request): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 401);
        }

        $msg = $this->chatMessageRepository->find($id);
        if (!$msg) {
            return new JsonResponse(['error' => 'Message non trouvé'], 404);
        }

        if ($msg->getIdUtilisateur() !== $user->getId()) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $event = $this->jpoRepository->find($msg->getIdRoom());
        if ($event && new \DateTime() > (clone $event->getDateEvenement())->modify('+12 hours')) {
            return new JsonResponse(['error' => 'Le chat est désormais en lecture seule (12h après le début)'], 403);
        }

        $content = json_decode($request->getContent(), true);
        $messageText = trim($content['message'] ?? '');

        if (empty($messageText)) {
            return new JsonResponse(['error' => 'Le message ne peut pas être vide'], 400);
        }

        $msg->setMessage($messageText);
        $msg->setIsEdited(true);
        $msg->setEditedAt(new \DateTime());

        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/delete/{id}', name: 'chat_delete_message', methods: ['POST'])]
    public function deleteMessage(int $id): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 401);
        }

        $msg = $this->chatMessageRepository->find($id);
        if (!$msg) {
            return new JsonResponse(['error' => 'Message non trouvé'], 404);
        }

        if ($msg->getIdUtilisateur() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $event = $this->jpoRepository->find($msg->getIdRoom());
        $deadline = $event ? (clone $event->getDateEvenement())->modify('+12 hours') : null;
        if (!$this->isGranted('ROLE_ADMIN') && $deadline && new \DateTime() > $deadline) {
            return new JsonResponse(['error' => 'Le chat est désormais en lecture seule (12h après le début)'], 403);
        }

        $msg->setStatus('deleted');
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{eventId}/presence', name: 'chat_update_presence', methods: ['POST'])]
    public function updatePresence(int $eventId): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 401);
        }

        $presence = $this->chatPresenceRepository->findByRoomAndUser($eventId, $user->getId());
        if (!$presence) {
            $presence = new ChatPresence();
            $presence->setIdRoom($eventId);
            $presence->setIdUtilisateur($user->getId());
            $this->entityManager->persist($presence);
        }

        $presence->setLastSeen(new \DateTime());
        $presence->setIsOnline(true);

        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
