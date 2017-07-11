<?php

namespace CoreBundle\Repository;

use CoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Repo class for user object
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * Saves user to the database
     *
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @param string $id
     * @return User
     */
    public function getUserById(string $id): User
    {
        return $this->findOneById($id);
    }

}
