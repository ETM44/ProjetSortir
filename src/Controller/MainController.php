<?php

namespace App\Controller;

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
     * @Route("/main", name="main")
     */
    public function index(InscriptionRepository $ir, SortieRepository $sr, Request $request): Response
    {
        $mainSearch = new MainSearch();

        $form = $this->createForm(MainFormType::class, $mainSearch);
        //dd($this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //dd($mainSearch);
        }

        if(empty($this->getUser())) {
            $results = $sr->findParticipantsInscritsWithFilter(0,$mainSearch);
            $userInscrSort = $ir->findUserSortie(0);
        } else {
            $results = $sr->findParticipantsInscritsWithFilter($this->getUser()->getId(),$mainSearch);
            $userInscrSort = $ir->findUserSortie($this->getUser()->getId());
        }



        //dd($results);
        return $this->render('main/index.html.twig', [
            'mainForm' => $form->createView(),
            'results' => $results,
            'userInscrSort' => $userInscrSort,
            'date' => new \DateTime('now')
        ]);
    }
}
