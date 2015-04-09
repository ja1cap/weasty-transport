<?php

namespace Weasty\Bundle\TransportBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OperativeInfoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperativeInfoRepository extends EntityRepository
{

  /**
   * @param OperativeInfo $entity
   *
   * @return $this
   */
  public function persistEntity( OperativeInfo $entity ){
    $this->getEntityManager()->persist($entity);
    return $this;
  }

  /**
   * @param $entities
   *
   * @return $this
   */
  public function flushEntities( $entities ){
    $this->getEntityManager()->flush($entities);
    return $this;
  }

}