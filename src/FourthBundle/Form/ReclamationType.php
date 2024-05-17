<?php

namespace KarimBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre',TextType::class,array(

            'attr' => array(
                'placeholder' => "Ecrivez un titre"
            )))
            ->add('nom',TextType::class,array(

                'attr' => array(
                    'placeholder' => "Votre nom"
                )))
            ->add('numtel',TextType::class,array(

                'attr' => array(
                    'placeholder' => "Votre numéro"
                )))
            ->add('mail',TextType::class,array(

                'attr' => array(
                    'placeholder' => "Votre mail"
                )))
            ->add('description',TextareaType::class,array(

            'attr' => array(
                'placeholder' => "Ecrivez un message"
            )));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reclamation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'karimbundle_reclamation';
    }


}
