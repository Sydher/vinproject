<?php

namespace App\Form\Admin;

use App\Entity\About;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AboutFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 100
                    ])
                ]
            ])
            ->add('value', TextType::class, [
                'required' => false
            ])
            ->add('textValue', TextareaType::class, [
                'required' => false
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['help'] = $options['help'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => About::class,
        ]);
    }

}
