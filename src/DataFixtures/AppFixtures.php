<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $Role1=new Role();
            $Role1->setLibeler('SUPADMIN');
            $Role2=new Role();
            $Role2->setLibeler('ADMIN');
            $Role3=new Role();
            $Role3->setLibeler('CAISSIER');
           
            $manager->persist($Role1);
            $manager->persist($Role2);
            $manager->persist($Role3);    
        $manager->flush();

        $User1=new User();
            $User1->setEmail('super');
            $User1->setRoles(['ROLE_SUPADMIN']);
            $User1->setPassword($this->passwordEncoder->encodePassword(
                         $User1,'super'));
            $User1->setisActive('true');
            $User1->setRole($Role1);

            $manager->persist($User1);    
            $manager->flush();
            
    }
}
