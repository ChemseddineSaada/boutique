<?php

namespace App\Form;

use App\Entity\{Product,Category};
use Symfony\Component\Form\{AbstractType,FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType,DateType,FileType,TextType,SubmitType,TextareaType};
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;


class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['label'=>'Nom'])
            ->add('description',TextareaType::class,['label'=>'Description'])
            ->add('published_at',DateType::class, array('format' => 'dd-MM-yyyy',))
            ->add('price', TextType::class,['label'=>'Prix'])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $manager) {
                return $manager->createQueryBuilder('c')
                ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => false, 
                'expanded' => false,
                'by_reference' => true,
                'label'=>'Catégorie',
                ])
            ->add('size', ChoiceType::class, ['choices' => ['XS' => 'xs','S' => 's','M' => 'm','L'=> 'l','XL'=>'xl',],'attr' => ['class'=>'horizontal'],'expanded' => true,'multiple' => true])
            ->add('image',FileType::class,['label'=>'Télécharger une image','data_class' => null])
            ->add('status', ChoiceType::class, ['choices' => [
                                                            'Publié' => 'publié',
                                                            'Brouillon' => 'brouillon',],'expanded' => true,])
            ->add('code', ChoiceType::class, ['choices' => [
                                                            'Normale' => 'normale',
                                                            'Solde' => 'solde',],])
            
            ->add('ref',TextType::class,['label'=>'Référence'])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
