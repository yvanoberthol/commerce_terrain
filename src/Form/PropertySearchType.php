<?php

namespace App\Form;

use App\Entity\Optione;
use App\Entity\PropertySearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxprice',IntegerType::class,[
                'required'=>false,
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Budget maximal'
                ]
            ])
            ->add('minsurface',IntegerType::class,[
                'required'=>false,
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Surface minimale'
                ]
            ])
            ->add('optiones',EntityType::class,[
                'class'=> Optione::class,
                'required'=>false,
                'label' => false,
                'multiple' => true,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method'=>'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
