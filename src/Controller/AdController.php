<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * Permet de créer une annonce 
     *
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     * 
     */
    public function formAds(Ad $ad = null, Request $request, ObjectManager $manager)
    {
    
            $ad = new Ad();
           
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

           
                $this->addFlash(
                    'success',
                    "Annonce {$ad->getTitle()} bien enregistrée"
                );
            


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
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous ne pouvez pas modifier une annonce qui n'est pas la votre !")
     */
    public function edit(Request $request, ObjectManager $manager, Ad $ad, string $slug) {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            
            $this->addFlash(
                'success', 
                "L'annonce <a href='{$this->generateUrl('ads_show', ['slug' => $ad->getSlug()])}'>{$ad->getTitle()}</a> a été modifiée avec succès !"
            );
            if($ad->getSlug() !== $slug) {
                return $this->redirectToRoute('ads_edit', ['slug' => $ad->getSlug()]);
            }
        }
        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


     /**
     * Supprimer une annonce
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'y accéder")
     * 
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {

        $manager->remove($ad);
        $manager->flush();

        $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");

        return $this->redirectToRoute('ads_index');
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
