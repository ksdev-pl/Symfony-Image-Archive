<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@example.com',
                'password' => 'admin'
            ],
            [
                'name'     => 'Normal',
                'email'    => 'normal@example.com',
                'password' => 'normal'
            ]
        ];

        foreach ($users as $user) {
            $entity = new User();
            $entity->setName($user['name']);
            $entity->setEmail($user['email']);
            $entity->setPassword($user['password']);

            $manager->persist($entity);
        }

        for ($i = 1; $i <= 30; $i++) {
            $entity = new User();
            $entity->setName("User #$i");
            $entity->setEmail("user$i@example.com");
            $entity->setPassword("user$i");

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
