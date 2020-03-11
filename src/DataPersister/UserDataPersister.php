<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager){
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        if ($data->getPassword()) {
            $checkIfNewPassword = $this->entityManager->getRepository(User::class)->findOneBy(array('id' => $data->getId(), 'password' => $data->getPassword()));
            if (!$checkIfNewPassword) {
                $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPassword()));
                $data->eraseCredentials();
            }
        }
          $role= $data->getRole();
        if ($role->getLibeler()==='SUPADMIN'){
          $data->setRoles(["ROLE_SUPADMIN"]);
        } if ($role->getLibeler()==='ADMIN'){
          $data->setRoles(["ROLE_ADMIN"]);
        } if ($role->getLibeler()==='CAISSIER'){
          $data->setRoles(["ROLE_CAISSIER"]);
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
      // call your persistence layer to save $data
      return $data;
    }

    public function remove($data, array $context = [])
    {
      // call your persistence layer to delete $data
    }
}