<?php

namespace DorraBundle\Form;

use AppBundle\Entity\Enfant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartActiviteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {$data= $options['user'];
        $builder->add('enfant', EntityType::class,[
            'class'=>Enfant::class,
            'query_builder' => function (EntityRepository $er) use ($data){
                return $er->createQueryBuilder('u')
                    ->where('u.parent='.$data->getId());
            },
            'choice_label'=>'nom',
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PartActivite',
            'user'=>null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_partactivite';
    }


}
