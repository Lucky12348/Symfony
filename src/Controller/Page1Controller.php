<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscription;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Employe;

class Page1Controller extends AbstractController
{
    #[Route('/page1', name: 'app_page1')]
    public function index(): Response
    {
        return $this->render('page1/index.html.twig', [
            'controller_name' => 'Page1Controller',
        ]);
    }
    #[Route("/affLesFormations", name: "app_aff")]
    public function afficherLesFilmsAction(ManagerRegistry $doctrine)
    {
        $formation = $doctrine->getManager()->getRepository(Formation::class)->findAll();

        if (!$formation) {
            $message = "Pas de formation";
        } else {
            $message = null;
        }
        return $this->render("formation/listeFormation.html.twig", array('ensFormation' => $formation, 'message' => $message));
    }
    //A FAIRE============================================================
    #[Route("/suppFormation/{id}", name: "app_film_sup")]
    public function suppFilmAction($id, ManagerRegistry $doctrine)
    {
        /**$formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);
        if ($formation) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->render('formation/listeFormation.html.twig', [
            'controller_name' => 'app_film_sup',
        ]);**/

        $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);
        if ($formation) {
            $inscription = $doctrine->getManager()->getRepository(Inscription::class)->verifInscriExiste($id);

            $entityManager = $doctrine->getManager();
            if (count($inscription) == 0) {
                $entityManager->remove($formation);
                $entityManager->flush();
            } else {
                return $this->render("formation/erreursup.html.twig");
            }
        }
        return $this->redirectToRoute('app_aff');
    }
    //END A FAIRE===========================================================

    #[Route('/ajoutFormation', name: 'app_ajoutFormation')]
    public function ajoutFormation(Request $request, ManagerRegistry $doctrine, $formation = null)
    {
        if ($formation == null) {
            $formation = new Formation();
        }
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('app_aff');
        }
        return $this->render('formation/editeur.html.twig', array('form' => $form->createView()));
    }



    // inscription formation employe
    #[Route('/inscriptionFormation/{id}', name: 'app_inscriptionFormation')]

    public function inscireFormation($id, Session $session, ManagerRegistry $doctrine)
    {
        $statut = null;
        if (!$session->get('employeId')) {
            return $this->redirectToRoute('app_connexion');
        }

        $formation = $doctrine->getManager()->getRepository(Inscription::class)->findOneBy(['laFromation' => $id]);
        if ($formation) {
            $statut = $formation->getLemploye();
        }
        if (!$session->get('employeId') == $statut) {

            $employe = $doctrine->getManager()->getRepository(Employe::class)->find($session->get('employeId'));
            $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);
            $inscription = new Inscription();
            $inscription->setLaFromation($formation);
            $inscription->setStatut("en cours");
            $inscription->setLemploye($employe);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($inscription);
            $entityManager->flush();
            return $this->redirectToRoute('app_enregistrer');
        } else {
            return $this->redirectToRoute('app_pasenregistrer');
        }
    }
    // VERIF SESSION INCLU DEDANS
    #[Route("/affDemandeInscription", name: "app_demandInscri")]
    public function afficherDemandeInscription(Session $session, ManagerRegistry $doctrine)
    {
        // VERIF SESSION
        if (!$session->get('employeId')) {
            return $this->redirectToRoute('app_connexion');
        } else {
            $employe = $doctrine->getManager()->getRepository(Employe::class)->findOneBy(['id' => $session->get('employeId')]);
            $statut = $employe->getStatut();
            if ($statut == 1) {
            } else {
                return $this->redirectToRoute('app_deconnexion');
            }
        }
        if (!$session->get('employeId')) {
            return $this->redirectToRoute('app_connexion');
        }
        // VERIF SESSION END
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->findAll();
        if (!$inscription) {
            $message = "Pas de formation";
        } else {
            $message = null;
        }
        return $this->render("inscription/listeDemande.html.twig", array('ensInscription' => $inscription, 'message' => $message));
    }

    //accepter inscription formation
    #[Route('/accepter/{id}', name: 'app_accepter')]
    public function accepter($id, ManagerRegistry $doctrine)
    {
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);
        $inscription->setStatut("accepter");
        $entityManager = $doctrine->getManager();
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->redirectToRoute('app_demandInscri');
    }

    #[Route('/refuser/{id}', name: 'app_refuser')]
    public function refuser($id, ManagerRegistry $doctrine)
    {
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);
        $inscription->setStatut("refuser");
        $entityManager = $doctrine->getManager();
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->redirectToRoute('app_demandInscri');
    }

    #[Route("/affAllEmploye", name: "app_affAllEmploye")]
    public function afficheAllEmploye(ManagerRegistry $doctrine)
    {
        $inscription = $doctrine->getManager()->getRepository(Employe::class)->findBy(["statut" => 0]);
        if (!$inscription) {
            $message = "pas de employe";
        } else {
            $message = null;
        }
        return $this->render("employe/afficheAllEmploye.html.twig", array('ensEmploye' => $inscription, 'message' => $message));
    }

    #[Route("/afficheAllInscriptionEmploye/{id}", name: "app_afficheAllInscriptionEmploye")]
    public function afficheAllInscriptionEmploye($id, ManagerRegistry $doctrine)
    {
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->findBy(["lemploye" => $id]);
        if (!$inscription) {
            $message = "pas de formation";
        } else {
            $message = null;
        }
        return $this->render("formation/afficheFormationidEmploye.html.twig", array('ensFormation' => $inscription, 'message' => $message));
    }
}
