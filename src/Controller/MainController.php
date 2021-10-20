<?php

namespace App\Controller;

use App\Bll\EtatUpdate;
use App\Form\AccueilFormType;
use App\Form\MainFormType;
use App\Bo\MainSearch;
use App\Repository\InscriptionRepository;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(InscriptionRepository $ir, SortieRepository $sr, Request $request): Response
    {
        $mainSearch = new MainSearch();

        $form = $this->createForm(AccueilFormType::class, $mainSearch);

        $form->handleRequest($request);

        $results = [];
        $userInscrSort = [];
        if($form->isSubmitted()) {
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
        if($form->isSubmitted()) {
            if(empty($this->getUser())) {
                $results = $sr->findParticipantsInscritsWithFilter(0,$mainSearch);
                $userInscrSort = $ir->findUserSortie(0);
            } else {
                $results = $sr->findParticipantsInscritsWithFilter($this->getUser()->getId(),$mainSearch);
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
}
