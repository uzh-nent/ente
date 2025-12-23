<?php

namespace App\Form\Traits;

use App\Enum\CodeSystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodedIdentifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('system', EnumType::class, ['class' => CodeSystem::class]);
        $builder->add('code', TextType::class);
        $builder->add('displayName', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'trait_coded_identifier',
        ]);
        parent::configureOptions($resolver);
    }
}
