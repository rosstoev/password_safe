<?php

declare(strict_types=1);

namespace App\Form;


use App\DTO\LoginTwoDTO;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginLevelTwoFormType extends AbstractType
{
    private GoogleAuthenticator $googleAuthenticator;

    public function __construct(GoogleAuthenticator $googleAuthenticator)
    {
        $this->googleAuthenticator = $googleAuthenticator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $secret = $this->googleAuthenticator->generateSecret();
        $encodedSecret = base64_encode($secret);
        $builder->add('secret', HiddenType::class, [
            'data' => $encodedSecret
        ]);
        $builder->add('code', TextType::class);

        $builder->add('check', SubmitType::class, [
            'label' => 'Провери'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LoginTwoDTO::class
        ]);
    }
}