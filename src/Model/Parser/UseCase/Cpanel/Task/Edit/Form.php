<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Edit;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
           'required'=>false,
           'label_attr'=>[
               'class'=>'required'
           ],
           'attr' => [
               'readonly'=>'readonly',
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
               'label'=>'Status',
               'attr' => [
                   'class' => 'form-select form-select-sm',
                   'data-control' => 'select2',
                   'data-placeholder' => 'Select status',
                   'data-hide-search' => 'true',
               ]
           ])
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Command::class);
    }
}