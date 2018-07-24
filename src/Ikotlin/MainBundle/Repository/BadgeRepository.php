<?php

/**
 * Created by PhpStorm.
 * User: Amal
 * Date: 03/12/2017
 * Time: 15:49
 */

namespace Ikotlin\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BadgeRepository extends EntityRepository {

    public function getUserBadges($id) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
                ->select('f.badgeindic')
                ->from('IKotlin\MainBundle\Entity\Badge', 'f')
                ->where("f.userid = :id")
                ->setParameter("id", $id)
        ;
        return $qb->getQuery()->getResult();
    }

    public function isUserHasBadge($id, $badgeindic) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
                ->select('IDENTITY(f.userid)', 'f.badgeindic')
                ->from('IKotlin\MainBundle\Entity\Badge', 'f')
                ->where("f.userid = :id AND f.badgeindic = :badgeindic")
                ->setParameter("id", $id)
                ->setParameter("badgeindic", $badgeindic)
        ;
        return $qb->getQuery()->getResult();
    }

}
