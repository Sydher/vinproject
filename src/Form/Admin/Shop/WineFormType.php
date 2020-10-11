<?php

namespace App\Form\Admin\Shop;

use App\Entity\Appellation;
use App\Entity\Productor;
use App\Entity\Region;
use App\Entity\Wine;
use App\Enum\WineColors;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichFileType;

class WineFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50
                    ])
                ]
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('appellation', EntityType::class, [
                'class' => Appellation::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('productor', EntityType::class, [
                'class' => Productor::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('color', ChoiceType::class, [
                'required' => true,
                'choices' => WineColors::getColorsChoice()
            ])
            ->add('year', TextType::class, [
                'label' => 'Millésime',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'max' => 4
                    ]),
                    new Regex([
                        'pattern' => '/^[\d]*$/',
                        'match' => true,
                    ])
                ]
            ])
            ->add('format', TextType::class, [
                'attr' => [
                    'placeholder' => 'PlaceHolderWineFormat',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'max' => 50
                    ])
                ]
            ])
            ->add('vintage', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 50
                    ])
                ],
                'required' => false
            ])
            ->add('degree', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2
                    ])
                ]
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('stock', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'DeleteCurrentImg',
                'download_uri' => false
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['help'] = $options['help'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Wine::class,
        ]);
    }

}
