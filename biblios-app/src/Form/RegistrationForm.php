<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'registration.form.first_name',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'registration.form.last_name',
            ])
            ->add('username', TextType::class, [
                'label' => 'registration.form.username',
                'required' => false,                
            ])
            ->add('email', EmailType::class, [
                'label' => 'registration.form.email',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'registration.form.roles.label',
                'choices' => [
                    'registration.form.roles.user' => 'ROLE_USER',
                    'registration.form.roles.moderator' => 'ROLE_MODERATOR',
                    'registration.form.roles.book_create' => 'ROLE_BOOK_CREATE',
                    'registration.form.roles.book_edit' => 'ROLE_BOOK_EDIT',
                    'registration.form.roles.admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
        
        if (!$options['is_edit']) {
            $builder
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                ->add('plainPassword', PasswordType::class, [
                    'label' => 'registration.form.password',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        // Ensure the password is not compromised
                        // This constraint checks against a list of compromised passwords   
                        new NotCompromisedPassword(),
                    ],
                ]);
        };
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // Option to determine if the form is for editing an existing user
        ]);
    }
}