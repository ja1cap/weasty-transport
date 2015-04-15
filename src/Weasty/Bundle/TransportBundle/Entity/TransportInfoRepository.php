<?php
namespace Weasty\Bundle\TransportBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class TransportInfoRepository
 * @package Weasty\Bundle\TransportBundle\Entity
 */
abstract class TransportInfoRepository extends EntityRepository {
  /**
   * @param $query
   * @param array $types
   *
   * @return array
   */
  public function search($query, $types = array()){

    if(!$query){
      return [];
    }

    $qb = $this->getEntityManager()->createQueryBuilder();
    $sql = "SELECT ti.id FROM TransportInfo ti WHERE ".$qb->expr()->andX(
        $qb->expr()->like('ti.content', "%$query%"),
        ($types ? $qb->expr()->in('ti.type', $types) : null)
      );

    $ids = $this->getEntityManager()->getConnection()->fetchColumn($sql);

    return $ids;
    //return $this->findBy(['id'=>$ids]);

  }

  /**
   * @return mixed
   */
  public function getType(){
    return $this->getDiscriminatorColumnValue();
  }

  /**
   * @return mixed
   */
  public function getDiscriminatorColumnValue(){
    return $this->getClassMetadata()->discriminatorValue;
  }

  /**
   * @return TransportInfo
   */
  public function createEntity(){
    $className = $this->getClassName();
    return new $className;
  }

  /**
   * @param TransportInfo $entity
   *
   * @return $this
   */
  public function persistEntity( TransportInfo $entity ){
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