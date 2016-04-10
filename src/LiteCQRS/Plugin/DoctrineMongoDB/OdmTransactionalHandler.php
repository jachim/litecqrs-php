<?php

namespace LiteCQRS\Plugin\DoctrineMongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use LiteCQRS\Bus\MessageHandlerInterface;

use Exception;


class OdmTransactionalHandler implements MessageHandlerInterface
{
    private $documentManager;
    private $next;

    public function __construct(DocumentManager $documentManager, MessageHandlerInterface $next)
    {
        $this->documentManager = $documentManager;
        $this->next = $next;
    }

    public function handle($message)
    {
        $this->next->handle($message);
        $this->documentManager->flush();

    }
}

