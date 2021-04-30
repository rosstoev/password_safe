<?php


namespace App\Form;


use App\DTO\LoginDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, [
            'label' => 'Емайл',
            'attr' => ['autocomplete' => 'off'],
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->add('password', PasswordType::class, [
            'label' => 'Парола',
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->add('login', SubmitType::class, [
            'label' => 'Влез'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => LoginDTO::class
        ]);
    }
}