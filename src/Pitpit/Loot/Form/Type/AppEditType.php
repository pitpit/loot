<?php

namespace Pitpit\Loot\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AppEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $form = $builder->add('description', 'textarea', array(
            'required' => false,
            'attr' => array('class' => 'input-xlarge', 'rows' => 4)
        ));
    }

    public function getName()
    {
        return 'app_edit';
    }
}