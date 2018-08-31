<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Smartphones');
        $manager->persist($category);

        $products = [
            [
                'title' => 'Xiaomi Mi A1',
                'description' => 'Xiaomi is a privately owned company that designs, develops, and sells smartphones, an Android-based OS, and other consumer electronics. Xiaomi also makes fitness trackers, TVs, air purifiers, and tablets. It has a skin for its Android phones and tablets – MIUI. The company largely sells its phones via flash sales in India.',
                'price' => 7000,
            ],
            [
                'title' => 'Samsung Galaxy S9',
                'description' => 'The Samsung Galaxy S9 is here with the camera re-imagined! Take professional quality photos with the dual aperture camera, and capture stunning pictures whether you’re in bright daylight, moonlight, or super low light. Slow down reality in Super Slow-Mo mode that allows you to record 960 frames per second so you can see every detail. Create an emoji that looks, sounds, and acts just like you with the AR Emoji feature. Enjoy an even more immersive entertainment experience with the Galaxy S9’s brilliant 5.8” infinity QHD display, now enhanced with Dolby Atmos surround sound speakers. Built with the amazingly fast Qualcomm® Snapdragon™ 845 processor, the Samsung Galaxy S9 can take full advantage of T-Mobile’s expanding Extended Range 4G LTE network which travels twice as far and provides 4 times better in building coverage. It’s Samsung’s fastest ever phone and it demands the fastest ever network.',
                'price' => 26000,
            ],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setCategory($category);
            $product->setTitle($data['title']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
