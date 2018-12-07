<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        for ($i=1; $i <= 30 ; $i++) { 

        $ad = new Ad;
        $title = $faker->sentence();
        $ad->setTitle($title)
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setIntroduction($faker->paragraph(2))
            ->setContent($faker->paragraph(4))
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5));
       
            for ($j=1; $j <= mt_rand(2,5); $j++) { 
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
         }

         $manager->flush();
    }
}
