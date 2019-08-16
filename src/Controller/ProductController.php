<?php

namespace App\Controller;

use App\Form\ProductFormType;
use Symfony\Component\Mime\MimeTypes;
use App\Entity\{Product,Client,Category};
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/admin")
 * La classe permettant de gérer les différentes méthodes de la gestion des produits (Entity Product)
 * dans le DASHBOARD 
 * @author Chemseddine
 * @method index, addProduct, updateProduct, deleteProduct, statistic
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="admin.product.index")
     * La fonction index récupère la liste des produits(App/Entity/Product) et les renvoies dans une vue.
     * 
     * @param request $request
     * @param PaginatorInterface $paginator
     * 
     * @return array $pagination to product/index.html.twig
     */

    public function index(Request $request, PaginatorInterface $paginator)
    {
        $products = $this->getDoctrine()->getRepository(Product::Class);

        $query = $products->createQueryBuilder('b')->getQuery();

        $pagination = $paginator->paginate($query,$request->query->getInt('page', 1),12);

        
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/product/add", name="admin.product.add")
     * La fonction addProduct crée une nouvelle instance de la classe Product
     * ainsi qu'un formulaire pour l'intégration des données
     * Vérifie et valide le formulaire
     * 
     * @param request $request
     * 
     * @return ProductFormType $form to product/create.html.twig
     */

    public function addProduct(Request $request){
        
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            $file = $product->getImage();

            if($file){

                $fileName= md5(uniqid()).'.'.$file->guessExtension();

                $file->move($this->getParameter('file_directory'),$fileName);
                $newFileName=$this->getParameter('new_file_directory').$fileName;

                $product->setImage($newFileName);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($form->getData());
                $manager->flush();

                $this->addFlash(
                    'notice_nice',
                    'Le produit a été ajouté avec succès !'
                    );

                
                return $this->redirectToRoute('admin.product.index');
            }
            else{
                return $this->render('product/create.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Ajouter un nouveau produit'
                    ]);
            }
        }

        return $this->render('product/create.html.twig', [
        'form' => $form->createView(),
        'title' => 'Ajouter un nouveau produit',
        ]);
    }

    /**
     * @Route("/product/update/{id}", name="admin.product.update")
     * 
     * La fonction updateProduct prend instance existante de la classe Product
     * et crée un formulaire pour la modification des données
     * Vérifie et valide le formulaire
     * 
     * @param request $request
     * @param Product $product
     * 
     * @return ProductFormType $form to product/create.html.twig
     */

    public function updateProduct(Product $product, Request $request){

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $product->getImage();

            if($file){
                $fileName= md5(uniqid()).'.'.$file->guessExtension();

                $file->move($this->getParameter('file_directory'),$fileName);
                $newFileName=$this->getParameter('new_file_directory').$fileName;

                $product->setImage($newFileName);
            }
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            $this->addFlash(
                'notice_nice',
                'Le produit a été mis à jour avec succès !'
                );

            
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('product/create.html.twig', [
        'form' => $form->createView(),
        'title' => 'Ajouter un nouveau produit'
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="admin.product.delete")
      * 
     * La fonction deleteProduct prend instance existante de la classe Product
     * et la supprime
     * 
     * @param Product $product
     * 
     * @return void
     */

    public function deleteProduct(Product $product){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();
        $this->addFlash(
            'notice_bad',
            'Le produit a été supprimé avec succès !'
            );

        return $this->redirectToRoute('admin.product.index');

    }
    
     /**
     * @Route("/product/statistic", name="admin.product.statistic")
     *  
     * La fonction statistic récupère des données et les renvoie 
     * dans une vue product/statistic.html.twig
     * 
     * 
     * @param request $request
     * @param PaginatorInterface $paginator
     * 
     * @return array $pagination
     * @return array $commandes
     * 
     */

    public function statistic(Request $request, PaginatorInterface $paginator){

        $clients = $this->getDoctrine()->getRepository(Client::Class);

        $all_client = $clients->findAll();

        foreach($all_client as $client){
            $commandes[] = $client->getCommandes();
        }

        $query = $clients->createQueryBuilder('b')->getQuery();

        $pagination = $paginator->paginate($query,$request->query->getInt('page', 1),12);

        return $this->render('product/statistic.html.twig',[
            'pagination'=>$pagination,
            'commandes'=>$commandes,
        ]);
    }
}
