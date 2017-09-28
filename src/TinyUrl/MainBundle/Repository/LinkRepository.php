<?php

namespace TinyUrl\MainBundle\Repository;
use TinyUrl\MainBundle\Entity\Link;

/**
 * LinkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LinkRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPopularLinks() {
        return $this->createQueryBuilder('l')
            ->orderBy('l.counter', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->execute();
    }

    public function findLastAddedLinks() {
        return $this->createQueryBuilder('l')
            ->orderBy('l.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->execute();
    }

    public function incrementCounter(Link $lien) {
        return $this->createQueryBuilder('l')
            ->update('TinyUrlMainBundle:Link', 'l')
            ->set('l.counter', 'l.counter + 1')
            ->where('l = :lien')
            ->setParameter('lien', $lien)
            ->getQuery()
            ->execute();
    }

    public function findLastComment() {
        return $this->createQueryBuilder('l')
            ->orderBy('l.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}
