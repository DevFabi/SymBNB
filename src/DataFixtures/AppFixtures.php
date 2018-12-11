<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppFixtures extends Fixture
{

    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;    
    }
    

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];
        for ($i=0; $i <=10 ; $i++) { 
           $user = new User();

           $genre = $faker->randomElement($genres);
           $picture = 'https://randomuser.me/api/portraits/';
           $pictureId = $faker->numberBetween(1, 99).'.jpg';

           if ($genre == "male") $pîcture = $picture . 'men/'. $pictureId;
           else $pîcture = $picture . 'women/'. $pictureId;

            $hash =  $this->encoder->encodePassword($user, 'password');

           $user->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->paragraph(3))
                ->setHash($hash)
                ->setPicture($picture);

                $manager->persist($user);
                $users[] = $user;
        }
        // Nous gérons les annonces
        for ($i=1; $i <= 30 ; $i++) { 

        $ad = new Ad;
        $title = $faker->sentence();

        $user = $users[mt_rand(0, count($users) -1)];

        $ad->setTitle($title)
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setIntroduction($faker->paragraph(2))
            ->setContent($faker->paragraph(4))
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);
       
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
