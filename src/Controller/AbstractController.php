<?php
declare(strict_types=1);

namespace App\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;
    protected Request $request;

    public function __construct(MessageBusInterface $messageBus, protected DocumentManager $documentManager, private readonly RequestStack $requestStack)
    {
        $this->messageBus = $messageBus;
        $this->request = $this->requestStack->getCurrentRequest();
    }

    protected function handleMessage(object $message): mixed
    {
        return $this->handle($message);
    }
}
