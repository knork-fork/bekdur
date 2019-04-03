<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'attr' => ['class' => 'username-field', 'placeholder' => 'korisničko ime'],
            'required' => false,
            'label' => false,
        ]);

        $builder->add('email', TextType::class, [
            'attr' => ['class' => 'email-field', 'placeholder' => 'e-mail adresa'],
            'required' => false,
            'label' => false,
        ]);

        $builder->add('password', PasswordType::class, [
            'attr' => ['class' => 'password-field', 'placeholder' => 'lozinka'],
            'required' => false,
            'label' => false,
        ]);

        $builder->add('password_repeat', PasswordType::class, [
            'mapped' => false,
            'attr' => ['class' => 'password-field', 'placeholder' => 'potvrdi lozinku'],
            'required' => false,
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
