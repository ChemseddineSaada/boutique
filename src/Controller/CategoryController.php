<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CategoryType;
use App\Entity\Category;

/**
 * @Route("/admin")
 * La classe permettant de gérer les différentes méthodes de la gestion des catégories (Entity Category)
 * dans le DASHBOARD 
 * @author Chemseddine
 * @method index, addCategory, updateCategory, deleteCategory
 */

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="admin.category.index")
     * La fonction index récupère la liste des catégories et les renvoies dans une vue.
     * 
     * @param request $request
     * @param PaginatorInterface $paginator
     * 
     * @return array $pagination to category/index.html.twig
     */

    public function index(Request $request, PaginatorInterface $paginator)
    {
        $categories = $this->getDoctrine()->getRepository(Category::Class);

        $query = $categories->createQueryBuilder('b')->getQuery();

        $pagination = $paginator->paginate($query,$request->query->getInt('page', 1),12);

        
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/category/add", name="admin.category.add")
     * La fonction addCategory crée une nouvelle instance de la classe Category
     * ainsi qu'un formulaire pour l'intégration des données
     * Vérifie et valide le formulaire
     * 
     * @param request $request
     * 
     * @return CategoryType $form to category/create.html.twig
     */

    public function addCategory(Request $request){
        
        $category = new Category();

        //Création du formulaire
        $form = $this->createForm(CategoryType::class, $category);

        //Maintien de la requête
        $form->handleRequest($request);

        //Véfication et validation
        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            //Ajout d'un message d'alerte
            $this->addFlash(
                'notice_nice',
                'La catégorie a été ajoutée avec succès !'
                );

            
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('category/create.html.twig', [
        'form' => $form->createView(),
        'title' => 'Ajouter une nouvelle catégorie'
        ]);
    }

    /**
     * @Route("/category/update/{id}", name="admin.category.update")
     * 
     * La fonction updateCategory prend instance existante de la classe Category
     * et crée un formulaire pour la modification des données
     * Vérifie et valide le formulaire
     * 
     * @param request $request
     * @param Category $category
     * 
     * @return CategoryType $form to category/create.html.twig
     */

    public function updateCategory(Category $category, Request $request){

        $form = $this->createForm(CategoryType::class, $category);

        
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            $this->addFlash(
                'notice_nice',
                'La catégorie a été mise à jour avec succès !'
                );

            
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('category/create.html.twig', [
        'form' => $form->createView(),
        'title' => 'Ajouter une nouvelle catégorie'
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="admin.category.delete")
     * 
     * La fonction deleteCategory prend instance existante de la classe Category
     * et la supprime
     * 
     * @param Category $category
     * 
     * @return void
     */

    public function deleteProduct(Category $category){

        $manager = $this->getDoctrine()->getManager();

        //Suppression de la catégorie ciblé     
        $manager->remove($category);
        $manager->flush();

        $this->addFlash(
            'notice_bad',
            'La catégorie a été supprimée avec succès !'
            );

        return $this->redirectToRoute('admin.category.index');

    }
}
