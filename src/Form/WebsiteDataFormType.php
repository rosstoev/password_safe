<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\WebsiteData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', TextType::class, [
            'label' => 'Име'
        ]);
        $builder->add('password', TextType::class, [
            'label' => 'Парола'
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Запази'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => WebsiteData::class
        ]);
    }
}