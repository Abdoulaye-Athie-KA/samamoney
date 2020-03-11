<?php

namespace App\DataPersister;

use Exception;
use App\Entity\Role;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class PartenaireDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager){
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Partenaire;
    }

    public function persist($data, array $context = [])
    {
        if ($data->getPassword()) {
            $checkIfNewPassword = $this->entityManager->getRepository(Partenaire::class)->findOneBy(array('id' => $data->getId(), 'password' => $data->getPassword()));
            if (!$checkIfNewPassword) {
                $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPassword()));
                $data->eraseCredentials();
            }
        }

        $data->setRole($this->entityManager->getRepository(Role::class)->find(4));
        $data->setRoles(['ROLE_PARTENAIRE']);

        if ($data->getMontant() < 500000) {
            throw new Exception("montant doit etre 500k ou plus", 1);
            
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        $compte = new Compte();
            $compte->setSolde($data->getMontant());
            $compte->setPartenaire($data);

            $this->entityManager->persist($compte);
            $this->entityManager->flush();

        $depot = new Depot();
            $depot->setMontant($data->getMontant());
            $depot->setCompte($compte);

            $this->entityManager->persist($depot);
            $this->entityManager->flush();
      // call your persistence layer to save $data
      return $data;
    }

    public function remove($data, array $context = [])
    {
      // call your persistence layer to delete $data
    }
}