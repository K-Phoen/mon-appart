<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\NewOfferFound;
use App\Repository\ConfigRepository;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class NotifyNewOffers implements EventSubscriberInterface
{
    private const FROM_EMAIL = 'no-reply@mailgun.kevingomez.fr';

    private $mailer;
    private $router;
    private $configRepo;
    private $titles = [];

    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, ConfigRepository $configRepo)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->configRepo = $configRepo;
    }

    public static function getSubscribedEvents()
    {
        return [
            NewOfferFound::class => 'onNewOfferFound',
            KernelEvents::TERMINATE => 'flush',
            ConsoleEvents::TERMINATE => 'flush',
        ];
    }

    public function onNewOfferFound(NewOfferFound $event): void
    {
        $offer = $event->offer();

        $this->titles[] = sprintf(
            '<li><a href="%s">%s (%d m², %d €)</a></li>',
            $this->router->generate('list_offers', [], RouterInterface::ABSOLUTE_URL).'#offer-'.$offer->id(),
            $offer->title(), $offer->area(), $offer->price()
        );
    }

    public function flush(): void
    {
        if (empty($this->titles)) {
            return;
        }

        $config = $this->configRepo->mainConfig();

        if (!$config->notificationsEnabled() || empty($config->notificationEmails())) {
            return;
        }

        $offersList = implode(PHP_EOL, $this->titles);

        $message = (new \Swift_Message(sprintf('[APPART] %s nouvelles offres ont été trouvées', count($this->titles))))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($config->notificationEmails())
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
