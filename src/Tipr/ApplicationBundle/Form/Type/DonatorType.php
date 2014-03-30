<?php

namespace Tipr\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DonatorType extends AbstractType {
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

        $builder->add('save', 'submit' , array(
            'label' => 'Save'
        ));

        parent::buildForm($builder,$options);
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