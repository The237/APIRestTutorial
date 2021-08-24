<?php

namespace App\Weather;

use Symfony\Component\HttpClient\HttpClient;
use JMS\Serializer\SerializerInterface;

class Weather
{
    private $weatherClient;
    private $serializer;
    private $apiKey;

    public function __construct(SerializerInterface  $serializer, $apiKey)
    {
        $this->weatherClient = HttpClient::create();
        $this->serializer = $serializer;
        $this->apiKey = $apiKey;
    }

    public function getCurrent()
    {
        try{
            $response = $this->weatherClient->request(
                'GET',
                'https://api.openweathermap.org/data/2.5/weather?'
                .'q=London,uk'
                .'&APPID='.$this->apiKey
            );

            $data = $this->serializer->deserialize($response->getContent(), 'array', 'json');

            return [
                'city' => $data['name'],
                'description' => $data['weather'][0]['main']
            ];
        }
        catch(\Exception $e){
            return ['error' => 'Les informations ne sont pas disponibles pour le moment.'];
        }
    }
}