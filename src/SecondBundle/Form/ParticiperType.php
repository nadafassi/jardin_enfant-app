<?php

namespace EmnaBundle\Form;

use AppBundle\Entity\Enfant;
use AppBundle\Repository\EnfantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticiperType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data= $options['user'];
        $jr = $options['jardin'];
        $builder->add('enfant',EntityType::class,[
            'class'=>Enfant::class,
            'choice_label'=>'prenom',
            'choices'=>$data,

        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Participer',
            'user'=>null,
            'jardin'=>null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_participer';
    }


}
