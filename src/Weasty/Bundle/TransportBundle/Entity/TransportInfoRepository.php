<?php
namespace Weasty\Bundle\TransportBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class TransportInfoRepository
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class TransportInfoRepository extends EntityRepository {

  /**
   * @param $type
   * @param $criteria
   * @param $orderBy
   * @param $limit
   * @param $offset
   * @return TransportInfo[]
   */
  public function findByType( $type, $criteria, $orderBy, $limit, $offset ){

    $qb = $this->createQueryBuilder('alias');


    foreach ($criteria as $criteriaField => $criteriaValue) {
      $qb->andWhere($qb->expr()->eq($criteriaField, $criteriaValue));
    }

    foreach ($orderBy as $orderField => $order) {
      $qb->orderBy($orderField, $order);
    }

    $qb
        ->setFirstResult($offset)
        ->setMaxResults($limit)
    ;

    $query = $qb->getQuery();

    $cacheKey = 'transport_items_'.md5($type.'_'.serialize($criteria).'_'.serialize($orderBy).'_'.$limit.'_'.$offset);
    $query->useResultCache(true, 60*5, $cacheKey);

    return $query->getResult();

  }

  /**
   * @param string $query
   * @param array $types
   * @param int $limit
   * @param int $offset
   *
   * @return array
   */
  public function search($query, $types = array(), $limit, $offset ){

    $qb = $this->getEntityManager()->createQueryBuilder();
    $tableName = $this->getClassMetadata()->table['name'];
    if(!$query){
      return [];
    }

    $validTypes = $this->getTypes();
    $types = array_filter($types, function($type) use ($validTypes) {
      return in_array($type, $validTypes);
    });

    $sql = "SELECT ti.id FROM $tableName ti WHERE ".$qb->expr()->andX(
        $qb->expr()->like('ti.content', "'%$query%'"),
        ($types ? $qb->expr()->in('ti.type', $types) : null)
      );
    $sql .= " LIMIT $limit OFFSET $offset";

    $idResults = $this->getEntityManager()->getConnection()->fetchAll($sql);
    $ids = array_map(function($idResult){
      return $idResult['id'];
    }, $idResults);

    if($ids){
      $qb
        ->select('ti')
        ->from($this->getEntityName(), 'ti')
        ->where($qb->expr()->in('ti.id', $ids))
      ;
      return $qb->getQuery()->getResult();
    }

    return [];

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
  public function getTypes(){
    return array_keys($this->getDiscriminatorMap());
  }

  /**
   * @return mixed
   */
  public function getDiscriminatorColumnValue(){
    return $this->getClassMetadata()->discriminatorValue;
  }

  /**
   * @return mixed
   */
  public function getDiscriminatorMap(){
    return $this->getClassMetadata()->discriminatorMap;
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