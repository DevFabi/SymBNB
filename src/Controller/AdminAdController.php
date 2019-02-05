<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdRepository;
use App\Entity\Ad;
use App\Form\AdType;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */ 
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll(),
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @return Response
     */ 
    public function edit(Ad $ad)
    {
        $form= $this->createForm(AdType::class, $ad);
        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' =>$form->createView()
        ]);
    }
}
