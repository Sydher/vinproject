<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\IsTrue;

class ConfirmDeleteFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('sureDelete', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should be sure you want to delete',
                    ]),
                ],
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['help'] = $options['help'] ?? '';
    }

}
