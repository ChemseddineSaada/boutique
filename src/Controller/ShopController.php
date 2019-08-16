<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\{Product,Client,Category};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class ShopController extends AbstractController
{
    
    /**
     * @Route("/shop", name="shop")
     * La fonction index récupère la liste des produits (Product Entity) et les renvoies dans une vue.
     * 
     * @param request $request
     * @param PaginatorInterface $paginator
     * 
     * @return array $pagination to shop/index.html.twig
     */

    public function index(Request $request, PaginatorInterface $paginator)
    {
        $products = $this->getDoctrine()->getRepository(Product::Class);

        $pagination = $this->paginer($request,$paginator,$products);

        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/paginer", name="paginer")
     * 
     * La fonction paginer permet de mettre en place une pagination rapide
     * à l'aide de l'interface PaginatorInterface
     * 
     * @param request $request
     * @param PaginatorInterface $paginator
     * @param mixte $topaginate
     * 
     * @return array $pagination
     *
     */

    public function paginer(Request $request, PaginatorInterface $paginator,$topaginate)
    {
        $query = $topaginate->createQueryBuilder('b')->where("b.status = 'publié'")->getQuery();

        $pagination = $paginator->paginate($query,$request->query->getInt('page', 1),6);

        $pagination->setCustomParameters(array(
            'align' => 'center', // css
            'size' => 'small',
            'style' => 'bottom',
            'span_class' => 'whatever'
            ));

        return $pagination;
    }


    /**
     * @Route("/mainMenu", name="main_menu")
     * 
     * La fonction mainMenu permet de récupérer les catégories et les renvoie 
     * à la vue partials/menu.html.twig pour l'affichage des catégories dans le menu
     * 
     * @param void
     * 
     * @return array $categories
     */
    
     public function mainMenu($currentSlug=''){
         $categories = $this->getDoctrine()->getRepository(Category::Class)->findALl();

         return $this->render('partials/menu.html.twig',[
             'categories' => $categories,
             'currentSlug'=>$currentSlug
         ]);
     }

    /**
     * @Route("/adminMenu", name="admin_menu")
     * 
     * La fonction adminMenu renvoie un tableau de données pour l'affichage
     * du menu du DASHBOARD.
     * 
     * @param void 
     * 
     * @return array $pages
     */
    
    public function adminMenu(){
        
        return $this->render('partials/admin.html.twig',[
            'pages'=>[
                'home' => ['url'=> 'shop', 'label'=> 'Retour à l\'accueil'],
                'dashboard'=>['url'=>'admin.product.index', 'label' => 'DASHBOARD'],
                'addArticle'=>['url'=>'admin.product.add', 'label' => 'Ajouter un produit +'],
                'Statistic'=>['url'=>'admin.product.statistic','label'=>'Statistique des commandes'],
                'UserRole'=>['url'=>'admin.user.index', 'label' => 'Changer les permissions'],
                'Category'=>['url'=>'admin.category.index','label'=>'Catégories'],
                'addCategory'=>['url'=>'admin.category.add','label'=>'Ajouter une Catégorie +'],
            ]
        ]);
    }

     /**
     * @Route("/shop/category/{slug}", name="shop.category.index")
     * 
     * La fonction showCategory récupère les catégories par nom {slug} et les renvoie 
     * à la vue shop/category.html.twig
     * 
     * @param string $slug
     * 
     * @return array $category
     * @return string $slug
     */

    public function showCategory($slug)
    {
        $category = $this->getDoctrine()->getRepository(Category::CLass)->findByName($slug);

        return $this->render('shop/category.html.twig',[
            'title' => 'La catégorie '.$slug,
            'slug' => $slug,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/shop/solde", name="shop.solde.index")
     * 
     * La fonction showSolde récupère les produits par code et les renvoie 
     * à la vue shop/solde.html.twig
     * 
     * @param void
     * 
     * @return array $products
     */

    public function showSolde(){
        $products = $this->getDoctrine()->getRepository(Product::CLass)->findByCode('solde');

        return $this->render('shop/solde.html.twig',[
            'title' => 'Les produits soldés',
            'products' => $products,
        ]);
    }

     /**
     * @Route("/shop/single/{id}", name="shop.single.index")
     * 
     * la fonction showSingle récupère un produit (Product Entity) 
     * à partir de son id ($id) et le renvoie à la vue shop/product.html.twig
     * 
     * @param int $id
     * 
     * @return array $product[0]
     */
    
    public function showSingle($id){

        $product = $this->getDoctrine()->getRepository(Product::CLass)->findById($id);

        return $this->render('shop/product.html.twig',[
            'title' => 'Fiche produit',
            'product' => $product[0],
        ]);
    }

    }

    




