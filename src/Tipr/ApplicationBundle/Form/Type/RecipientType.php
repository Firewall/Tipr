<?php

namespace Tipr\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RecipientType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('emailaddress', 'text',array(
            'constraints' => array(
                new \Symfony\Component\Validator\Constraints\Email(),
            )
        ));

        $builder->add('place', 'text',array(
        ));

        $builder->add('activity', 'choice',array(
            'choices'   => array(
                'Charity'   => 'Charity',
                'Street performer' => 'Street performer',
                'Non-profit organisation'   => 'Non-profit organisation',
            ),
        ));

        $builder->add('about', 'textarea',array(
        ));

        $builder->add('standardamount', 'number',array(
        ));

        $builder->add('goal', 'number',array(
        ));

        $builder->add('send', 'submit' , array(
            'label' => 'Send'
        ));

        $builder->add('facebook', 'text',array(
        ));

        $builder->add('twitter', 'text',array(
        ));

        $builder->add('youtube', 'text',array(
        ));

        $builder->add('showstats', 'checkbox', array(
            'label'     => 'Show this entry publicly?',
            'required'  => false,
        ));

        $builder->add('save', 'submit' , array(
            'label' => 'Edit settings',
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'account';
    }
} 