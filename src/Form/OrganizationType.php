<?php

namespace App\Form;

use App\Entity\Organization;
use App\Form\Traits\CodedIdentifierType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('address', CodedIdentifierType::class, ['inherit_data' => true, 'label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_organization',
            'data_class' => Organization::class,
        ]);
        parent::configureOptions($resolver);
    }
}
