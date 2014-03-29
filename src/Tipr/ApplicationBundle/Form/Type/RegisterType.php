<?php

namespace Tipr\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('birthDate', 'text',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('documentNumber', 'text',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('code', 'number',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('send', 'submit' , array(
            'label' => 'Send'
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