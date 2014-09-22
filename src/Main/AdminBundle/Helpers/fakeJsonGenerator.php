<?php
namespace Main\AdminBundle\Helpers;

use \Bazinga\Bundle\FakerBundle\Factory;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class fakeJsonGenerator  {

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public function jsonGeneratorWithNesting(){
        $json = [];
        $faker = \Faker\Factory::create();
        for($i = 0; $i < 50; ++$i){
            $json[$i] = [
                'id' => $faker->numberBetween(1,1000),
                'name' => $faker->name,
                'lastname' => $faker->lastName,
                'address' => $faker->address

            ];
            for($j = 0; $j < 3; ++$j){
                $json[$i]['children'][$j] = [
                    'name' => $faker->name,
                    'age' => $faker->randomNumber(2)
                ];
            }
        }
        return $this->serializer->serialize($json, 'json');
    }

    public function jsonGenerator(){
        $json = [];
        $faker = \Faker\Factory::create();
        for($i = 0; $i < 50; ++$i){
            $json[$i] = [
                'id' => $faker->numberBetween(1,1000),
                'name' => $faker->name,
                'lastname' => $faker->lastName,
                'address' => $faker->address

            ];
        }
        return $this->serializer->serialize($json, 'json');
    }
}