<?php

namespace LiteCQRS\Plugin\DoctrineMongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use LiteCQRS\Bus\IdentityMap\IdentityMapInterface;
use LiteCQRS\EventProviderInterface;

class ODMIdentityMap implements IdentityMapInterface
{
    private $documentManager;
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function add(EventProviderInterface $object)
    {
        $this->documentManager->persist($object);
    }

    public function all()
    {
        $aggregateRoots = array();
        $uow            = $this->documentManager->getUnitOfWork();

        foreach ($uow->getIdentityMap() as $class => $entities) {
            foreach ($entities as $entity) {
                if (!($entity instanceof EventProviderInterface)) {
                    break;
                }

                $aggregateRoots[] = $entity;
            }
        }

        return $aggregateRoots;
    }

    public function getAggregateId(EventProviderInterface $object)
    {
        $class = $this->documentManager->getClassMetadata(get_class($object));
        return $class->identifier ? $class->getIdentifierValue($object) : null;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}

