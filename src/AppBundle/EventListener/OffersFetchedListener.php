<?php

namespace AppBundle\EventListener;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

use Bundle\AppBundle\Entity\Offer;

class OffersFetchedListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'offers.fetched' => 'onOffersFetched',
        );
    }

    public function onOffersFetched(GenericEvent $event)
    {
        $offers  = $event->getSubject();

        if (count($offers) === 0) {
            return;
        }

        $message = $this->getMessage($offers);

        $this->mailer->send($message);
    }

    private function getMessage(array $offers)
    {
        $offersList = implode(PHP_EOL, array_map(function($offer) {
            return sprintf('<li>%s</li>', $offer->getTitle());
        }, $offers));

        return Swift_Message::newInstance()
            ->setSubject(sprintf('[APPART] %s nouvelles annonces ont été trouvées', count($offers)))
            ->setFrom('contact+appart@kevingomez.fr')
            ->setTo('contact@kevingomez.fr')
            ->setBody(<<<MSG
<ul>
    $offersList
</ul>
Bah, cf le titre.
MSG
, 'text/html'
            );
    }
}
