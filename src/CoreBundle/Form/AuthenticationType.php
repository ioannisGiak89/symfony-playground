<?php

namespace CoreBundle\Form;

use CoreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Log in form
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class AuthenticationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("username", TextType::class)
            ->add("password", PasswordType::class)
            ->add("target_path", HiddenType::class, [
                "data" => "/admin"
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Sign in"
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "csrf_protection" => true,
            "csrf_field_name" => "token",
            // a unique key to help generate the secret token
            // enhances the security of the generated token by making it different for each form
            "csrf_token_id"   => md5("sign_in_form"),
        ]);

    }

}
