<?php

namespace Tipr\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DonateType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', 'number',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('username', 'text',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('code', 'password',array(
            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
        ));

        $builder->add('donate', 'submit' , array(
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