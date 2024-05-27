<?php

namespace App\EventSubscriber;

use App\Entity\JwtToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events; 

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtTokenSubdcriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_ENCODED => 'onJWTEncodedEvent',
        ];
    }
    public function onJWTEncodedEvent(JWTEncodedEvent $event): void
    {
        $token = new JwtToken();
        $token->setToken($event->getJWTString());

        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }

}
