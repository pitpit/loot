<?php

namespace Pitpit\Loot\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AppNameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $form = $builder->add('name', 'text', array(
            'attr' => array('autocomplete' => 'off')
        ));
    }

    public function getName()
    {
        return 'app_name';
    }
}