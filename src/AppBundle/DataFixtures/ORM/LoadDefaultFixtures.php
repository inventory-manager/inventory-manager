<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Models\ArticleCategory;
use AppBundle\Models\Role;
use AppBundle\Models\RoomType;
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

        // User erstellen
        $user = User::createUser(
            null,
            'user',
            'us',
            'er',
            'user@user.com',
            '$2y$05$sKaS99Shc5p9.hKthB886ed7fTlT4yhPs9KAtaYymj6j.2lWy0aqa' // user
        );
        $user->addToRoles($userRole);

        $user->setCreatedBy($superAdmin);
        $user->setEditedBy($superAdmin);
        $manager->persist($user);


        // RoomTypes erstellen
        $classRoom = new RoomType();
        $classRoom->setCreatedBy($superAdmin);
        $classRoom->setEditedBy($superAdmin);
        $classRoom->setDescription('Klassenraum');
        $manager->persist($classRoom);

        $serverRoom = new RoomType();
        $serverRoom->setCreatedBy($superAdmin);
        $serverRoom->setEditedBy($superAdmin);
        $serverRoom->setDescription('Serverraum');
        $manager->persist($serverRoom);

        $storeRoom = new RoomType();
        $storeRoom->setCreatedBy($superAdmin);
        $storeRoom->setEditedBy($superAdmin);
        $storeRoom->setDescription('Abstellraum');
        $manager->persist($storeRoom);

        // Article Category Types
        $newCat = new ArticleCategory();
        $newCat->setCreatedBy($superAdmin);
        $newCat->setEditedBy($superAdmin);
        $newCat->setDescription('Monitor');
        $manager->persist($newCat);

        $newCat = new ArticleCategory();
        $newCat->setCreatedBy($superAdmin);
        $newCat->setEditedBy($superAdmin);
        $newCat->setDescription('PC');
        $manager->persist($newCat);

        $newCat = new ArticleCategory();
        $newCat->setCreatedBy($superAdmin);
        $newCat->setEditedBy($superAdmin);
        $newCat->setDescription('Peripheriegeräte');
        $manager->persist($newCat);

        $newCat = new ArticleCategory();
        $newCat->setCreatedBy($superAdmin);
        $newCat->setEditedBy($superAdmin);
        $newCat->setDescription('Kabel');
        $manager->persist($newCat);

        $manager->flush();
    }
}
