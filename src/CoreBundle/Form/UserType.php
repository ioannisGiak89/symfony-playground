<?php

namespace CoreBundle\Form;

use CoreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Registration form builder
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("email", EmailType::class)
            ->add("username", TextType::class)
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options"  => ["label" => "Password"],
                "second_options" => ["label" => "Repeat Password"],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Join us!"
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => User::class,
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            // enhances the security of the generated token by making it different for each form
            "csrf_token_id"   => md5("registration_form"),
        ]);
    }
}
