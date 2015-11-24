<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTestFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // TODO: TestFixtures laden
    }
}
