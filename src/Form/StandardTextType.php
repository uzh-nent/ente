<?php

namespace App\Form;

use App\Entity\LeadingCode;
use App\Entity\Specimen;
use App\Entity\StandardText;
use App\Enum\InterpretationGroup;
use App\Enum\Pathogen;
use App\Form\Traits\CodedIdentifierType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class StandardTextType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pathogen', EnumType::class, ['class' => Pathogen::class, 'required' => false, 'help' => 'help.pathogen']);
        $builder->add('text', TextareaType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_standard_text',
            'data_class' => StandardText::class,
        ]);
        parent::configureOptions($resolver);
    }
}
