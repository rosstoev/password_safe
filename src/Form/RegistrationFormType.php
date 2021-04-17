<?php

declare(strict_types=1);

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, [
            'required' => false,
            'attr' => ['autocomplete' => "off"],
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->add('password', PasswordType::class, [
            'attr' => ['autocomplete' => "new-password"],
           'constraints' => [
               new NotBlank()
           ]
        ]);

        $builder->add('firstName', TextType::class, [
            'constraints' =>[
                new NotBlank()
            ]
        ]);

        $builder->add('familyName', TextType::class, [
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->add('register', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => User::class
        ]);
    }
}