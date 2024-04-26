<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\Booking;
use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use Symfony\Component\Validator\Constraints\Timezone;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            
            /** @var Restaurant $restaurant */
            // $restaurant = $this->getReference("restaurant" . random_int(1,20));
            $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1,20));
            
            $booking = (new Booking())
            // ->setGuestNumber(150)
            ->setGuestNumber(random_int(90,1000))
            ->setOrderDate($faker->dateTime($timezone = null))
            // ->setOrderHour($faker->time('H-i-s'))
            ->setAllergy("Cacahuètes")
            
            ->setRestaurant($restaurant)
            
            ->setCreatedAt(new DateTimeImmutable());
            
            $manager->persist($booking);
        }
        
        $manager->flush();
        
    }

    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}