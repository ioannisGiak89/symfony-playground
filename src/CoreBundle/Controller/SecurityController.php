<?php

namespace CoreBundle\Controller;

use CoreBundle\Form\AuthenticationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    public function loginAction(AuthenticationUtils $authUtils, FormFactoryInterface $formFactory , TokenStorage $tokenStorage, AuthorizationChecker $authChecker)
    {
        // Check if user is logged in
        if ($authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render("CoreBundle:Security:login.html.twig", [
                "user" => $tokenStorage->getToken()->getUser(),
                // this works only in controller
                // "user" => $this->getUser()
            ]);
        } else {
            // build the form
            $form = $formFactory->create(AuthenticationType::class);

            // get the login error if there is one
            $error = $authUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authUtils->getLastUsername();

            return $this->render("CoreBundle:Security:login.html.twig", [
                "last_username" => $lastUsername,
                "error"         => $error,
                "form"          => $form->createView()
            ]);
        }
    }
}
