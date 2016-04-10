<?php

namespace LiteCQRS\Plugin\DoctrineMongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;

class OdmHandlerFactory
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function __invoke($handler)
    {
        return new OdmTransactionalHandler($this->documentManager, $handler);
    }
}

