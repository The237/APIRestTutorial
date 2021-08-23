<?php

namespace App\EventSubscriber;

use App\Normalizer\NormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
/**
 * use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
 * bien vouloir charger la classe ci-dessous car la précédent est dépassée
 */
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private $serializer;
    private $normalizers;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function processException(ExceptionEvent $event)
    {
        $result = null;
        $exception = $event->getThrowable();
        
        if (is_array($this->normalizers)) {
            foreach ($this->normalizers as $normalizer) {
                if ($normalizer->supports($exception)) {
                    $result = $normalizer->normalize($event->getThrowable());
                    break;
                }
            }
        }
        
        
        if (null == $result) {
            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body'] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $event->getThrowable()->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $event->setResponse(new Response($body, $result['code']));
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }
}