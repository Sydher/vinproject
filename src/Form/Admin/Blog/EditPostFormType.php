<?php

namespace App\Form\Admin\Blog;

use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EditPostFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50
                    ])
                ]
            ])
            ->add('tags', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50
                    ])
                ],
                "help" => "TagsHelper"
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'DeleteCurrentImg',
                'download_uri' => false
            ])
            ->add('content', CKEditorType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a content',
                    ]),
                    new Length([
                        'min' => 5,
                    ])
                ],
                'attr' => array('rows' => '10')
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['help'] = $options['help'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }

}
