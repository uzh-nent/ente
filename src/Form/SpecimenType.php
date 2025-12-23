<?php

namespace App\Form;

use App\Entity\Specimen;
use App\Form\Traits\CodedIdentifierType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecimenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('codedIdentifier', CodedIdentifierType::class, ['inherit_data' => true, 'label' => false]);
        $builder->add('specimenGroup', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_specimen',
            'data_class' => Specimen::class,
        ]);
        parent::configureOptions($resolver);
    }
}
