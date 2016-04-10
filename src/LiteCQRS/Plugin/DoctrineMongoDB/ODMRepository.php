<?php

namespace LiteCQRS\Plugin\DoctrineMongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use LiteCQRS\DomainEventProviderRepositoryInterface;
use LiteCQRS\EventProviderInterface;

class ODMRepository implements DomainEventProviderRepositoryInterface
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function find($class, $id)
    {
        return $this->documentManager->find($class, $id);
    }

    public function add(EventProviderInterface $object)
    {
        $this->documentManager->persist($object);
    }

    public function remove(EventProviderInterface $object)
    {
        $this->documentManager->remove($object);
    }
}
