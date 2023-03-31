<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Form\ConnexionType;
use App\Form\EmployeType;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }
    #[Route('/inscription', name: 'app_inscription')]
    public function ajoutFormation(Request $request, ManagerRegistry $doctrine, $employe = null)
    {
        if ($employe == null) {
            $employe = new Employe();
        }
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('app_connexion');
        }
        return $this->render('employe/editeur.html.twig', array('form' => $form->createView()));
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(Request $request, ManagerRegistry $doctrine, $employe = null)
    {
        if ($employe == null) {
            $employe = new Employe();
        }
        $form = $this->createForm(ConnexionType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $login = $form['login']->getData();
            $mdp = $form['mdp']->getData();
            $employe = $doctrine->getManager()->getRepository(Employe::class)->verifConnexion($login, $mdp);
            if ($employe == null) {
                return $this->redirectToRoute('app_connexion');
            } else {
                $session = new Session();
                $session->set('employeId', $employe->getId());
                if ($employe->getStatut() == 0) {
                    return $this->redirectToRoute('app_affEmploye');
                } else {
                    //redirection vers inscription formation
                    return $this->redirectToRoute('app_aff');
                }
            }
        }
        return $this->render('employe/connexion.html.twig', array(
            'form' => $form->createView(),
            'form_class' => 'laClass'
        ));
    }
    #[Route("/affLesFormationsEmploye", name: "app_affEmploye")]
    public function afficherFormationEmploye(Session $session, ManagerRegistry $doctrine)
    {
        if (!$session->get('employeId')) {
            return $this->redirectToRoute('app_connexion');
        }
        $formation = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        if (!$formation) {
            $message = "Pas de formation";
        } else {
            $message = null;
        }
        return $this->render("formation/afficheFormationInscription.html.twig", array('ensFormation' => $formation, 'message' => $message));
    }
    #[Route("/affLesFormationsListeEmploye", name: "app_affFormationEmploye")]
    public function affLesFormationsListeEmploye(Session $session, ManagerRegistry $doctrine)
    {

        if (!$session->get('employeId')) {
            return $this->redirectToRoute('app_connexion');
        }
        $employe = $doctrine->getManager()->getRepository(Employe::class)->find($session->get('employeId'));
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['lemploye' => $employe]);

        if (!$inscription) {
            $message = "Pas de formation";
        } else {
            $message = null;
        }
        return $this->render("employe/afficheFormationInscriptionEmploye.html.twig", array('ensFormation' => $inscription, 'message' => $message));
    }

    #[Route('/affFormationAnnee', name: 'app_FormaAnnee')]
    public function affFormationAnnee(Request $request, ManagerRegistry $doctrine)
    {
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findFormationDeLannee();
        return $this->render('formation/enregistrer.html.twig');
    }

    #[Route('/enregistrer', name: 'app_enregistrer')]
    public function enregister(Request $request, ManagerRegistry $doctrine)
    {
        return $this->render('formation/enregistrer.html.twig');
    }

    #[Route('/pasenregistrer', name: 'app_pasenregistrer')]
    public function pasenregister(Request $request, ManagerRegistry $doctrine)
    {
        return $this->render('formation/pasEnregistrer.html.twig');
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion(Session $session)
    {
        // Supprimer la variable de session 'employeId'
        $session->remove('employeId');
        return $this->redirectToRoute('app_connexion');
    }
}
