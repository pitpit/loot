<?php

namespace Digitas\Demo\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Damien Pitard <dpitard at digitas dot fr>
 * @copyright Digitas France
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array('required' => true));
        $builder->add('firstname', 'text', array('required' => true));
        $builder->add('lastname', 'text', array('required' => true));
    }

    public function getName()
    {
        return 'user_type';
    }
}
