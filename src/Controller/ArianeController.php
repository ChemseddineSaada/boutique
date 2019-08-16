<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\{Product};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * La classe permettant de gérer les différentes méthodes du fil 
 * d'ariane.
 * @author Chemseddine
 * @method index
 */
class ArianeController extends AbstractController
{
    /**
     * @Route("/ariane",name="ariane.ariane.show")
     * La fonction index récupère le route depuis le template Twig
     * et le transforme en un fil d'ariane suivant la logique des mes routes
     * 
     * @param string $path
     * @param array $data_array
     * 
     * @return array $arianes to templates/partials/ariane.html.twig
     */
    public function index($path,$data_array){
        
        $links=explode(".",$path);
        $arianes=[];

        // Vérifiction de l'existence de l'id ou le slug pour le bon fonctionnement de certaine pages.

        if(array_key_exists('id',$data_array)){
            $product = $this->getDoctrine()->getRepository(Product::CLass)->findById($data_array['id']);
            $name = $product[0]->getName(); 
            $id = $data_array['id'];
            $slug = $product[0]->getCategory()->getName();
        }
        else{ 
            if(array_key_exists('slug',$data_array)){
                $slug = $data_array['slug'];
            }
            else{
                $slug = '';
            }
            $name = ''; 
            $id = null;
        }

        // Déclaration d'un tableau de mot clés dans les routes 

        $pages=[
            'shop'=>['url'=>'shop','label'=>'Accueil'],
            'category'=>['url'=>'shop.category.index','label'=>'Catégorie '.$slug,'slug'=>$slug],
            'single'=>['url'=>'shop.single.index','label'=>$name,'id'=>$id],
            'solde'=>['url'=>'shop.solde.index','label'=>'Soldes']
        ];

        // Assignation des valeurs en fonction du route
        
        foreach($links as $link){
            if(array_key_exists ($link,$pages)){
                if($link=='single'){
                    $arianes[] = $pages['category'];
                }
                $arianes[] = $pages[$link];
            }
        }
                
        return $this->render('partials/ariane.html.twig',[
            'arianes'=>$arianes
        ]);
}
}