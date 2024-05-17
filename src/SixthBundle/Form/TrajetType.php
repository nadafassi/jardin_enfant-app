<?php

namespace SamiBundle\Form;

use AppBundle\Entity\Chauffeur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TrajetType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {$data= $options['user'];

        $builder->add('adresse')->add('heure')->add('chauffeur',EntityType::class,[
            'class'=>Chauffeur::class,
            'query_builder' => function (EntityRepository $er) use ($data){
                return $er->createQueryBuilder('u')
                    ->where('u.jardin='.$data->getId());
            },
            'choice_label'=>'nom',
            'multiple'=>false
        ]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trajet',
            'user'=>null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trajet';
    }


}
