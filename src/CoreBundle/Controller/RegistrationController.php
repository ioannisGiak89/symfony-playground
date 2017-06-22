<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\User;
use CoreBundle\Form\UserType;
use CoreBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Registration Controller
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class RegistrationController extends Controller
{
    public function registerAction(Request $request, UserRepository $userRepository, FormFactoryInterface $formFactory)
    {
        // 1) build the form
        $user = new User();
        $form = $formFactory->create(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            // We actually do that with a Listener: CoreBundle\EventListener\PasswordEncoder
            // $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            // $user->setPassword($password);

            // 4) save the User!
            $userRepository->save($user);

            return $this->redirectToRoute("login");
        }

        return $this->render(
            "CoreBundle:Registration:register.html.twig",
            ["form" => $form->createView()]
        );
    }

}
