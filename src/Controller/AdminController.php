<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CreerUtilisateurType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    #[Route('/utilisateur', name: 'admin_gerer_utilisateur')]
    public function indexUtilisateurActif(ParticipantRepository $participantRepository): Response
    {
        $utilisateursActif = $participantRepository->afficherTousLesUtilisateursActifs();
        dump($utilisateursActif);
        return $this->render('admin/gererUtilisateur.html.twig', [
            'utilisateursActif'=>$utilisateursActif
        ]);
    }

    #[Route('/utilisateurNonActif', name: 'admin_gerer_utilisateur_non_actif')]
    public function indexUtilisateurNonActif(ParticipantRepository $participantRepository): Response
    {
        $utilisateursActif = $participantRepository->afficherTousLesutilisateursInactifs();
        return $this->render('admin/gererUtilisateurNonActif.html.twig', [
            'utilisateursActif'=>$utilisateursActif
        ]);
    }

    #[Route('/supprimerUtilisateur/{id}', name: 'admin_supprimer_utilisateur')]
    public function supprimerUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantASupprimer = $participantRepository->find($id);

        $participantRepository->remove($participantASupprimer, true);

        $this->addFlash('success', 'Utilisateur supprimé');

        return $this->redirectToRoute('admin_gerer_utilisateur');
    }

    #[Route('/desactiverUtilisateur/{id}', name: 'admin_desactiver_utilisateur')]
    public function desactiverUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantADesactiver = $participantRepository->find($id);
        $participantADesactiver->setActif(0);
        $participantRepository->save($participantADesactiver, true);

        $this->addFlash('success', 'Utilisateur modifié');


        return $this->redirectToRoute('admin_gerer_utilisateur');
    }

    #[Route('/activerUtilisateur/{id}', name: 'admin_activer_utilisateur')]
    public function activerUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantAActiver = $participantRepository->find($id);
        $participantAActiver->setActif(1);
        $participantRepository->save($participantAActiver, true);

        $this->addFlash('success', 'Utilisateur Activé');


        return $this->redirectToRoute('admin_gerer_utilisateur_non_actif');
    }
    #[Route('/creerutilisateur', name: 'admin-creer-utilisateur')]
    public function ajouterUnUtilisateur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager):Response
    {
        $user = new Participant();
        $form = $this->createForm(CreerUtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAdministrateur(false);
            $user->setActif(true);
            $user->setRoles(['ROLE_USER']);
            $user->setImageName('default.jpg');
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'l\'utilisateur a bien été crée !');
            // do anything else you need here, like send an email
            return $this->redirectToRoute('admin_gerer_utilisateur');
        }

        return $this->render('registration/creerUtilisateur.html.twig',[
            'registrationForm'=>$form->createView()
        ]);

    }
    #[Route('/attributionadmin/{id}', name:'admin_attribution_role', methods: ['GET', 'POST'])]
    public function attribution(int $id, ParticipantRepository $participantRepository)
    {
        $nouvelAdmin = $participantRepository->find($id);
        $nouvelAdmin->setRoles(roles: (array)'ROLE_ADMIN');
        $participantRepository->save($nouvelAdmin, true);
        $this->addFlash("success", 'L\'opération s\'est bien passé');
        return $this->redirectToRoute('admin_gerer_utilisateur', ['id' => $nouvelAdmin->getId()], Response::HTTP_SEE_OTHER);
    }



}
