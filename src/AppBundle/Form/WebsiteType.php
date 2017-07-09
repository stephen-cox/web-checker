<?php

namespace AppBundle\Form;

use AppBundle\Entity\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class WebsiteType extends AbstractType {
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder
      ->add('id', HiddenType::class)
      ->add('displayName', TextType::class)
      ->add('url', TextType::class)
      ->add('checkString', TextType::class)
      ->add('save', SubmitType::class);
  }
  
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    
    $resolver->setDefaults(array(
      'data_class' => Website::class,
    ));
  }
  
  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    
    return 'appbundle_website';
  }
  
  
}
