<?php

namespace App\Form;

use App\Entity\Organism;
use App\Enum\Pathogen;
use App\Form\Traits\CodedIdentifierType;
use App\Form\Traits\HideableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganismType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('hideable', HideableType::class, ['inherit_data' => true, 'label' => false]);
        $builder->add('codedIdentifier', CodedIdentifierType::class, ['inherit_data' => true, 'label' => false]);
        $builder->add('organismGroup', TextType::class, ['required' => false]);
        $builder->add('pathogen', EnumType::class, ['class' => Pathogen::class, 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_organism',
            'data_class' => Organism::class,
        ]);
        parent::configureOptions($resolver);
    }
}
