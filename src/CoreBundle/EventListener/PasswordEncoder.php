<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Doctrine event listener for encrypting the password
 * before saving the user
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class PasswordEncoder
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPassEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userPassEncoder = $passwordEncoder;
    }

    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $plainPass = $user->getPlainPassword();

        if (!$plainPass) {
            return;
        }

        $password = $this->userPassEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }
}
