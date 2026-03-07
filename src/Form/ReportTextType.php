<?php

namespace App\Form;

use App\Entity\ReportText;
use App\Enum\Pathogen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportTextType extends AbstractType
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
            'translation_domain' => 'entity_report_text',
            'data_class' => ReportText::class,
        ]);
        parent::configureOptions($resolver);
    }
}
