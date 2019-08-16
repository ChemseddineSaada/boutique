<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * La classe permettant de gérer les méthodes d'inscription et d'authentification
 * 
 * @author Chemseddine
 * @method signUp, loginPanel
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="sign_up")
     * 
     * La fonction signUp crée un formulaire d'inscription,
     * récupère les données saisies, vérifie leurs validités
     * procède au hashage du mot de passe et au stockage des données
     * 
     * @param request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return UserType $form to security/sign.html.twig
     */

    public function signUp(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user,$user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('shop');
        }

        return $this->render('security/sign.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

     /**
     * @Route("/login", name="login_panel")
     * 
     * La fonction loginPanel récupère et traite les saisies d'authentification
     * 
     * @param AuthenticationUtils $authenticationUtils
     * 
     * @return string  $lastUsername
     * @return array  $error
     */

    public function loginPanel(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
