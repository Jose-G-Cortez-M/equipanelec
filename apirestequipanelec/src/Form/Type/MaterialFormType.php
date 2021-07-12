<?php

namespace App\Form\Type;

use App\Form\Model\MaterialDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaterialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('base64Imagen', TextType::class)
            ->add('movimientos', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => MovimientoFormType::class
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MaterialDto::class,
            'csrf_protection' => false
        ]);
    }

    //funciones para no especificar el formulario dentro de el json
    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }

}