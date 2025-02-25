<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ✅ Création d'un administrateur
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // ✅ Création d'une équipe
        $equipe = new User();
        $equipe->setEmail('equipe@example.com');
        $equipe->setRoles(['ROLE_EQUIPE']);
        $equipe->setPassword($this->passwordHasher->hashPassword($equipe, 'equipe123'));
        $manager->persist($equipe);

        // ⚡ Enregistrement des utilisateurs en base de données
        $manager->flush();
    }
}
