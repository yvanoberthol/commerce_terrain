<?php

namespace App\Form;

use App\Entity\Optione;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat',ChoiceType::class,['choices' => $this->getHeatChoices()])
            ->add('city')
            ->add('optiones', EntityType::class,[
                'class' => Optione::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('address')
            ->add('postal_code')
            ->add('imageFile',FileType::class,[
                'required'=>false
            ])
            ->add('sold')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain'=> 'forms'
        ]);
    }

    private function getHeatChoices(): array
    {
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $key => $value){
            $output[$value] = $key;
        }
        return $output;
    }
}
