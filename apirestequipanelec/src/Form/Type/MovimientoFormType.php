<?php

namespace App\Form\Type;

use App\Form\Model\MovimientoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MovimientoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('nombre', TextType::class);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovimientoDto::class,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }

}