<?php
/**
 * User: jachim
 * Date: 10/04/16
 * Time: 15:29
 */

namespace LiteCQRS;

use Doctrine\Common\Persistence\Mapping\MappingException;
use LiteCQRS\Bus\IdentityMap\IdentityMapInterface;
use LiteCQRS\Plugin\Doctrine\DoctrineIdentityMap;
use LiteCQRS\Plugin\DoctrineMongoDB\ODMIdentityMap;

class ORMODMIdentityMap implements IdentityMapInterface
{

    /**
     * @var DoctrineIdentityMap
     */
    private $doctrineIdentityMap;
    /**
     * @var ODMIdentityMap
     */
    private $mongoIdentityMap;

    public function __construct(DoctrineIdentityMap $doctrineIdentityMap, ?ODMIdentityMap $ODMIdentityMap=null)
    {
        $this->doctrineIdentityMap = $doctrineIdentityMap;
        $this->mongoIdentityMap = $ODMIdentityMap;
    }

    public function add(EventProviderInterface $object)
    {
        if($this->isFromMongo($object)) {
            $this->mongoIdentityMap->add($object);
        } else {
            $this->doctrineIdentityMap->add($object);
        }
    }

    public function all()
    {
        return array_merge($this->doctrineIdentityMap->all(), $this->mongoIdentityMap ? $this->mongoIdentityMap->all() : []);
    }

    public function getAggregateId(EventProviderInterface $object)
    {
        if($this->isFromMongo($object)) {
            return $this->mongoIdentityMap->getAggregateId($object);
        } else {
            return $this->doctrineIdentityMap->getAggregateId($object);
        }
    }

    /**
     * @param EventProviderInterface $object
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    private function isFromMongo(EventProviderInterface $object)
    {
        try {
            return $this->mongoIdentityMap && $this->mongoIdentityMap->getDocumentManager()->getClassMetadata(get_class($object));
        } catch(MappingException $ex) {
            return false;
        }
    }
}