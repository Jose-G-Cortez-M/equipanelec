<?php

namespace App\Form\Type;

use App\Form\Model\MovimientoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ramsey\Uuid\Uuid;

class MovimientoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('nombre', TextType::class);
            $builder->get('id')->addModelTransformer(new CallbackTransformer(
                function ($id) {
                    if ($id === null) {
                        return '';
                    }
                    return $id->toString();
                },
                function ($id) {
                    return $id === null ? null : Uuid::fromString($id);
                }
            ));

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovimientoDto::class,
            'csrf_protection' => false
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