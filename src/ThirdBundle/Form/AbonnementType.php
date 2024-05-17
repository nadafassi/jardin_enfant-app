<?php

namespace FeridBundle\Form;

use AppBundle\Entity\Enfant;
use AppBundle\Entity\Jardin;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityRepository;
use FeridBundle\Controller\EnfantController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class AbonnementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {$data= $options['user'];
        $builder->add('date')->add('type',ChoiceType::class,[
            'choices'=>[
                'Bus'=>'bus',
                'Normal'=>'normal'
            ],])->add('jardin', EntityType::class,[
                'class' => Jardin::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,

            ])->add('enfant', EntityType::class,[
            'class' => Enfant::class,
            'query_builder' => function (EntityRepository $er) use ($data){
                return $er->createQueryBuilder('u')
                    ->where('u.parent='.$data);
            },
            'choice_label' => 'prenom',

            'expanded' => false,
            'multiple' => false
        ])->add('etat',ChoiceType::class,[
            'choices'=>[
                'Attente'=>'attente'

            ],]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Abonnement',
            'user'=>null,
        ));

    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_abonnement';
    }


}
