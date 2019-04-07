<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

class UserRegister
{
    private $em;
    private $passwordEncoder;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function register(User $user, String &$errorMsg) : bool
    {
        if ($this->validate($user, $errorMsg))
        {  
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $user->setCreated(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function validate(User $user, String &$errorMsg) : bool
    {
        if (!$this->checkEmpty($user, $errorMsg))
        {
            return false;
        }

        if (!$this->checkDuplicate($user))
        {
            $errorMsg = "Korisničko ime već postoji";
            return false;
        }

        return true;
    }

    public function checkEmpty(User $user, String &$errorMsg) : bool
    {
        if ($user->getUsername() === "")
        {
            $errorMsg = "Korisničko ime ne može biti prazno";
            return false;
        }

        if ($user->getEmail() === "")
        {
            $errorMsg = "E-mail adresa ne može biti prazna";
            return false;
        }

        if ($user->getPassword() === "" || $user->getPasswordRepeat() === "")
        {
            $errorMsg = "Lozinka ne može biti prazna";
            return false;
        }

        if ($user->getPassword() !== $user->getPasswordRepeat())
        {
            $errorMsg = "Potvrda lozinke je neispravna";
            return false;
        }

        return true;
    }

    public function checkDuplicate(User $user) : bool
    {
        $entity = $this->userRepository->findOneBy([ 
            'username' => $user->getUsername()
        ]);
    
        return ($entity == null);
    }
}
