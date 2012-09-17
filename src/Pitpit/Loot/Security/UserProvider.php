<?php

namespace Pitpit\Loot\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\ORM\EntityManager;

class UserProvider implements UserProviderInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository('Pitpit\Loot\Entity\User')->findOneByEmail($username);
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('User with email "%s" does not exist.', $username));
        }

        // $stmt = $this->conn->executeQuery('SELECT * FROM users WHERE username = ?', array(strtolower($username)));

        // if (!$user = $stmt->fetch()) {
        //     throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        // }
        // 
        //@todo set roles
        if ($user->getIsDeveloper())
        {
            $roles = array('ROLE_DEVELOPER');
        } else {
            $roles = array('ROLE_USER');
        }
        

        return new User($user->getEmail(), $user->getPassword(), $roles, true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}