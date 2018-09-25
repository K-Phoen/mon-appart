<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\NewOfferFound;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class NotifyNewOffers implements EventSubscriberInterface
{
    private const FROM_EMAIL = 'no-reply@mailgun.kevingomez.fr';

    private $mailer;
    private $destinationMails = [];
    private $titles = [];

    public function __construct(\Swift_Mailer $mailer, array $destinationMails)
    {
        $this->mailer = $mailer;
        $this->destinationMails = $destinationMails;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            NewOfferFound::class => 'onNewOfferFound',
            KernelEvents::TERMINATE => 'flush',
            ConsoleEvents::TERMINATE => 'flush',
        );
    }

    public function onNewOfferFound(NewOfferFound $event): void
    {
        $offer = $event->offer();

        $this->titles[] = sprintf('<li>%s (%d m², %d €)</li>', $offer->title(), $offer->area(), $offer->price());
    }

    public function flush(): void
    {
        if (empty($this->titles)) {
            return;
        }

        $offersList = implode(PHP_EOL, $this->titles);

        $message = (new \Swift_Message(sprintf('[APPART] %s nouvelles offres ont été trouvées', count($this->titles))))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($this->destinationMails)
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
