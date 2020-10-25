<?php

namespace App\Form;

use App\Entity\Appellation;
use App\Entity\Productor;
use App\Entity\SearchProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'SearchProductFormNamePlaceHolder'
                ]
            ])
            ->add('appellation', EntityType::class, [
                'required' => false,
                'class' => Appellation::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('productor', EntityType::class, [
                'required' => false,
                'class' => Productor::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'PriceMinPlaceHolder'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'PriceMaxPlaceHolder'
                ]
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['help'] = $options['help'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    /**
     * Améliore l'URL (n'affiche pas SearchData au début).
     * @return string|null
     */
    public function getBlockPrefix() {
        return '';
    }

}
