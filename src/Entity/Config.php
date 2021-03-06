<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Config
{
    public const MAIN_CONFIG = 'main';

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id = self::MAIN_CONFIG;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $notificationEmails = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $notificationsEnabled = true;

    public function notificationEmails(): array
    {
        return $this->notificationEmails;
    }

    public function setNotificationEmails(array $emails): void
    {
        $this->notificationEmails = $emails;
    }

    public function setNotificationsEnabled(bool $status): void
    {
        $this->notificationsEnabled = $status;
    }

    public function notificationsEnabled(): bool
    {
        return $this->notificationsEnabled;
    }
}
