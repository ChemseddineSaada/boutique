<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ProductFormType;
use App\Entity\{Product,Client,User};
use Symfony\Component\Mime\MimeTypes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
* @Route("/admin")

 * La classe permettant de gérer les différentes méthodes lié aux utilisateurs 
 * (Entity User) dans la DASHBOARD 
 * d'ariane.
 * @author Chemseddine
 * @method index, update
 */

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="admin.user.index")
     * 
     * La fonction index permet de récupérer la liste des utilisateurs (User) 
     * et les renvoie avec une pagination à la vue user/index.html.twig
     * 
     * @param request $request
     * @param PaginatorInterface $paginator 
     * 
     * @return array $pagination
     */

    public function index(Request $request, PaginatorInterface $paginator)
    {
        $users = $this->getDoctrine()->getRepository(User::Class);

        $query = $users->createQueryBuilder('b')->getQuery();

        $pagination = $paginator->paginate($query,$request->query->getInt('page', 1),12);

        
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'pagination' => $pagination,
        ]);
    }

   /**
     * @Route("/user/update/{id}", name="admin.user.update")
     * 
     * La fonction update permet de récupérer les roles des utilisateurs 
     * (User) et les renvoie dans un formulaire à la vue 
     * user/update.html.twig
     * Vérifie et valide les données saisies. 
     * 
     * @param request $request
     * @param User $user 
     * 
     * @return UserFormType $form
     */

    public function update(Request $request, User $user)
    {
        $form = $this->createForm(UserFormType::class, $user);

        
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            $this->addFlash(
                'notice_nice',
                'Les permissions ont bien été modifiées avec succès'
                );

            
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('user/update.html.twig', [
        'form' => $form->createView(),
        'title' => 'Modifier les permissions'
        ]);
    }

}
