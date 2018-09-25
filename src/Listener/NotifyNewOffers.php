<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\NewOfferFound;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class NotifyNewOffers implements EventSubscriberInterface
{
    private $mailer;
    private $titles = [];

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            NewOfferFound::class => 'onNewOfferFound',
            KernelEvents::TERMINATE => 'flush',
        );
    }

    public function onNewOfferFound(NewOfferFound $event): void
    {
        $offer = $event->offer();

        $this->titles[] = sprintf('<li>%s</li>', $offer->title());
    }

    public function flush(): void
    {
        $offersList = implode(PHP_EOL, $this->titles);

        $message = (new \Swift_Message(sprintf('[APPART] %s nouvelles offres ont été trouvées', count($this->titles))))
            ->setFrom('no-reply@mailgun.kevingomez.fr')
            ->setTo('contact@kevingomez.fr')
            ->setBody(<<<MSG
<ul>
    $offersList
</ul>
Bah, cf le titre.
MSG
                , 'text/html'
            );
        ;

        $this->mailer->send($message);
    }
}
