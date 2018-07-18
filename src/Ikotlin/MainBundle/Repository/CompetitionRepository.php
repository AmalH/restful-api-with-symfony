<?php
/**
 * Created by PhpStorm.
 * User: Amal
 * Date: 11/01/2018
 * Time: 01:36
 */

namespace Ikotlin\MainBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CompetitionRepository extends EntityRepository
{
    public function getCompetitions($starts_at,$length,$level,$order){

        if($order=="1") $order="c.created";
        if($order=="2") $order="c.solved";

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('c.id','c.title','c.created',
                'c.solved','c.level',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')

            ->from('IkotlinMainBundle:Competition', 'c')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.idUser = u.id'
            )

            ->where("c.level = :level")
            ->setParameter("level",$level)
            ->orderBy($order, 'DESC')
            ->setFirstResult($starts_at)
            ->setMaxResults($length)
        ;

                //echo $qb;
            return $qb->getQuery()->getResult();
    }

    public function getCompetitionOptimized($idcomp){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('c.id','c.title','c.created',
                'c.solved','c.level','c.content',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')
            ->from('Ikotlin\MainBundle\Entity\Competition', 'c')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.idUser = u.id'
            )
            ->where("c.id = :idcomp")
            ->setParameter("idcomp",$idcomp)
        ;
        return $qb->getQuery()->getResult();
    }

}