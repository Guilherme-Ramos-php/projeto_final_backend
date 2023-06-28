<?php

namespace App\Form;

use App\DTO\MovimentacoesDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimentacoesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('conta', TextType::class)
            ->add('acao', TextType::class)
            ->add('descricao', TextType::class)
            ->add('valor', NumberType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MovimentacoesDTO::class,
            'csrf_protection' => false
        ]);
    }
}