<?php

namespace App\Controller;

use App\Form\MainFormType;
use App\Bo\MainSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(Request $request): Response
    {
        $mainSearch = new MainSearch();

        $form = $this->createForm(MainFormType::class, $mainSearch);
        //dd($this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //dd($mainSearch);
        }
        
        return $this->render('main/index.html.twig', [
            'mainForm' => $form->createView()
        ]);
    }
}
