<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\Menu;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            
            /** @var Restaurant $restaurant */
            // $restaurant = $this->getReference("restaurant" . random_int(1,20));
            $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1,20));
            $title = "Menu n°$i";
            
            $menu = (new Menu())
            ->setTitle($title)
            // ->setTitle($faker->$title())
            // ->setDescription("La description de mon menu")
            ->setDescription($faker ->text(30))
            
            ->setRestaurant($restaurant)
            
            ->setCreatedAt(new DateTimeImmutable());
            
            $manager->persist($menu);
        }
        
        $manager->flush();
        
    }

    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}