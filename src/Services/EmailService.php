<?php

namespace App\Services;

use App\Entity\ReportEmail;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\FileServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;

readonly class EmailService implements EmailServiceInterface
{
    public function __construct(private FileServiceInterface $fileService, private MailerInterface $mailer, private LoggerInterface $logger, private string $mailerSender)
    {
    }

    public function sendReportEmail(ReportEmail $email, ?string &$error = null): bool
    {
        $templatedEmail = new TemplatedEmail();

        $folder = $this->fileService->getFolderPath(FileServiceInterface::REPORT_FOLDER);
        $filepath = $folder . DIRECTORY_SEPARATOR . $email->getReport()->getFilename();

        dump($email->getReceiversArray(), $email->getCCReceiversArray(), $this->mailerSender);
        $templatedEmail
            ->to(...$email->getReceiversArray())
            ->cc(...$email->getCCReceiversArray())
            ->bcc($this->mailerSender)
            ->subject($email->getSubject())
            ->text($email->getBody())
            ->addPart(DataPart::fromPath($filepath, 'report.pdf', 'application/pdf'));

        try {
            $this->mailer->send($templatedEmail);

            $email->setSentAt(new \DateTimeImmutable());
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }
}
