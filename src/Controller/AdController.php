<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Permet de créer une annonce et d'en éditer une
     *
     * @Route("/ads/new", name="ads_create")
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     * 
     */
    public function formAds(Ad $ad = null, Request $request, ObjectManager $manager)
    {
        $formEdition = true;
        if (!$ad) {
            $ad = new Ad();
            $formEdition = false;
        }
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();

            if ($formEdition == false) {
                $this->addFlash(
                    'success',
                    "Annonce {$ad->getTitle()} bien enregistrée"
                );
            } else {
                $this->addFlash(
                    'success',
                    "Annonce {$ad->getTitle()} bien modifiée"
                );
            }


            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render(
            'ad/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad)
    {

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
