<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Create;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contract', TextType::class, [
                'label' => 'Contract',
                'required' => false,
                'label_attr' => [
                    'class' => 'required'
                ],
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Enter contract'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Active' => true,
                    'Not active' => false
                ],
                'data' => '1',
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-select form-select-sm',
                    'data-control' => 'select2',
                    'data-placeholder' => 'Select status',
                    'data-hide-search' => 'true',
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Enter title'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Enter description'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Command::class);
    }
}