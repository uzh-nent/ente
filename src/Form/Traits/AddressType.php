<?php

namespace App\Form\Traits;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('addressLines', TextareaType::class, ['required' => false]);
        $builder->add('postalCode', TextType::class, ['required' => false]);
        $builder->add('city', TextType::class, ['required' => false]);
        $builder->add('countryCode', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'trait_address',
        ]);
        parent::configureOptions($resolver);
    }
}
