<?php

namespace App\Serializer\Listener;

use JMS\Serializer\EventDispatcher\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;


class ArticleListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return([
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => 'App\Entity\Article',
                'method' => 'onPostSerialize'
            ]
        );
    }

    public static function onPostSerialize (ObjectEvent $event)
    {
        $date = new \DateTime();
        $event->getVisitor()->visitProperty(new StaticPropertyMetadata('', 'deliver_at', null), $date->format('l jS \of F Y h:i:s A'));
    }
}