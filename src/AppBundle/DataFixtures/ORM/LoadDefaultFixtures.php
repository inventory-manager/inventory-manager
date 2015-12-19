<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Models\Role;
use AppBundle\Models\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadDefaultFixtures
 * @package AppBundle\DataFixtures\ORM
 */
class LoadDefaultFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Rollen erstellen
        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');
        $adminRole->setDescription('Administrator');
        $manager->persist($adminRole);

        $userRole = new Role();
        $userRole->setName('ROLE_USER');
        $userRole->setDescription('Benutzer');
        $manager->persist($userRole);


        // Superadmin erstellen
        $superAdmin = User::createUser(
            null,
            'admin',
            'ad',
            'min',
            'admin@admin.com',
            '$2y$13$R2aqy59kAEV0YczhMmrN3.JDTzg4/2JC41HLFxFikB2.VD8B5NFK2' // admin
        );
        $superAdmin->addToRoles($adminRole);

        $superAdmin->setCreatedBy($superAdmin);
        $superAdmin->setEditedBy($superAdmin);
        $manager->persist($superAdmin);

        $manager->flush();
    }
}
