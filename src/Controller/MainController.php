<?php

namespace App\Controller;

use App\Bll\EtatUpdate;
use App\Form\AccueilFormType;
use App\Form\MainFormType;
use App\Bo\MainSearch;
use App\Repository\InscriptionRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use App\Entity\Inscription;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(InscriptionRepository $ir, SortieRepository $sr, Request $request): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('main');
        }

        $mainSearch = new MainSearch();
        $form = $this->createForm(AccueilFormType::class, $mainSearch);
        $form->handleRequest($request);

        $results = [];
        $userInscrSort = [];
        if ($form->isSubmitted()) {
            $mainSearch->setSortiePasInscrit(true);
            $results = $sr->findSortieOuverteWithFilter($mainSearch);
        }

        return $this->render('main/accueil.html.twig', [
            'mainForm' => $form->createView(),
            'results' => $results,
            'userInscrSort' => $userInscrSort,
            'date' => new \DateTime('now')
        ]);
    }

    /**
     * @Route("/main", name="main")
     */
    public function index(InscriptionRepository $ir, SortieRepository $sr, Request $request): Response
    {
        $mainSearch = new MainSearch();

        $form = $this->createForm(MainFormType::class, $mainSearch);

        $form->handleRequest($request);

        $results = [];
        $userInscrSort = [];
        if ($form->isSubmitted()) {
            if (empty($this->getUser())) {
                $results = $sr->findParticipantsInscritsWithFilter(0, $mainSearch);
                $userInscrSort = $ir->findUserSortie(0);
            } else {
                $results = $sr->findParticipantsInscritsWithFilter($this->getUser()->getId(), $mainSearch);
                $userInscrSort = $ir->findUserSortie($this->getUser()->getId());
            }
        }

        return $this->render('main/index.html.twig', [
            'mainForm' => $form->createView(),
            'results' => $results,
            'userInscrSort' => $userInscrSort,
            'date' => new \DateTime('now')
        ]);
    }

    /**
     * @Route("main/inscrire/{id}", name="app_inscrire")
     */
    public function inscrire(EntityManagerInterface $em, SortieRepository $sortieRepository, $id = 0): Response
    {

        $sortie = $sortieRepository->find($id);
        $inscription = new Inscription();

        if(count($sortie->getInscriptions()) >= $sortie->getNbInscriptionsMax()){
            $this->addFlash('warning', 'Le nombre maximal de participants a déjà été atteint ! ');
            return $this->redirectToRoute("main");
        }

        if ($sortie->getEtat()->getId() < 2) {
            $this->addFlash('warning', 'Vous ne pouvez pas encore vous inscrire à cet événement ! ');
            return $this->redirectToRoute("main");
          } elseif ($sortie->getEtat()->getId() >= 3) {
            $this->addFlash('warning', 'Les inscriptions sont terminées pour cet événement ! ');
            return $this->redirectToRoute("main");
          } else {
            $inscription->setParticipant($this->getUser());
            $inscription->setDateInscription(new \DateTime('now'));
            $inscription->setSortie($sortie);

            $em->persist($inscription);
            $em->flush();

            $this->addFlash('success', 'Votre inscription a bien été prise en compte ! ');
            return $this->redirectToRoute("main");
        }
    }

    /**
     * @Route("main/desister/{id}", name="app_desister")
     */
    public function desister(EntityManagerInterface $em, InscriptionRepository $inscriptionRepository, $id = 0): Response
    {
        $inscription = $inscriptionRepository->findUserIdAndSortieId($this->getUser()->getId(), $id);

        $em->remove($inscription);
        $em->flush();

        $this->addFlash('success', 'Vous avez été désinscrit de cette sortie. ');
        return $this->redirectToRoute("main");

    }
}
