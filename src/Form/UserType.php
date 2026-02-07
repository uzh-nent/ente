<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public const string PLAIN_PASSWORD = 'plainPassword';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, ['help' => 'help.name']);
        $builder->add('abbreviation', TextType::class, ['help' => 'help.abbreviation']);
        $builder->add('shortname', TextType::class, ['help' => 'help.shortname']);
        $builder->add('isEnabled', CheckboxType::class, ['required' => false, 'help' => 'help.is_enabled']);
        $builder->add('medicalValidation', CheckboxType::class, ['required' => false, 'help' => 'help.medical_validation']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_user',
            'data_class' => User::class,
        ]);
        parent::configureOptions($resolver);
    }
}
