<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
       $error= $utils->getLastAuthenticationError();
       $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

     /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){
    }

     /**
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
      $user = new User();

      $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
           $manager->persist($user);
           $manager->flush();

           $this->addFlash(
               'success',
               "Votre compte a bien été crée! Connectez vous"
           );

           return $this->redirectToRoute('account_login');
        }
      return $this->render('account/registration.html.twig',
                          [ 'form' =>$form->createView()]);
    }

    /**
     * Modification du profil utilisateur
     *
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){
        $user = $this->getUser();
        $form= $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($user);
           $manager->flush();

           $this->addFlash(
               'success',
               'Compte bien modifié');

        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

/**
 * @Route("/account/update-password", name="account_password")
 * @IsGranted("ROLE_USER")
 *
 * @return Response
 */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $passwordUpdate = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $user = $this->getUser();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier que le old password soit le même que l'utilisateur
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash()) ) {
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel!"));
            } else{
                // Encoder et sauvegarder
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié'
                );

                return $this->redirectToRoute('homepage');
            }
            

          
        }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher un profil d'utilisateur 'mon compte'
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */

    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations d'un utlisateur
     *
     * @Route("/account/bookings", name="account_bookings")
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
