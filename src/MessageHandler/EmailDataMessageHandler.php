<?php

namespace App\MessageHandler;

use App\Message\EmailDataMessage;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailDataMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(EmailDataMessage $messageData)
    {
        $message = new NotificationEmail();
        $message
            ->subject($messageData->getCompanyName())
            ->htmlTemplate('emails/user_notification.html.twig')
            ->from('yaroslawbondarets@gmail.com')
            ->to($messageData->getUserEmail())
            ->context([
                'startDate' => $messageData->getStartDate(),
                'endDate' => $messageData->getEndDate(),
            ]);

        $this
            ->mailer
            ->send($message);
        ;
    }
}