<?php

namespace App\Form;

use App\Entity\LeadingCode;
use App\Entity\Specimen;
use App\Enum\Pathogen;
use App\Form\Traits\CodedIdentifierType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class LeadingCodeType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('codedIdentifier', CodedIdentifierType::class, ['inherit_data' => true, 'label' => false]);
        $builder->add('pathogen', EnumType::class, ['class' => Pathogen::class]);
        $builder->add('organismGroup', TextType::class, ['required' => false]);
        $builder->add('specimen', EntityType::class, ['required' => false, 'class' => Specimen::class,
            'choice_label' => function (Specimen $specimen): string {
                $code = $specimen->getSystem()->trans($this->translator) . " " . $specimen->getCode();
                return $specimen->getDisplayName() . " (" . $code . ")";
            }]);
        $builder->add('specimenGroup', TextType::class, ['required' => false]);
        $builder->add('interpretationGroup', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_leading_code',
            'data_class' => LeadingCode::class,
        ]);
        parent::configureOptions($resolver);
    }
}
