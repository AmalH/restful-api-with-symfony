<?php
/**
 * Created by PhpStorm.
 * User: Amal
 * Date: 11/01/2018
 * Time: 14:25
 */

namespace Ikotlin\MainBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Ikotlin\MainBundle\Entity\User;

class CompetitionAnswerRepository extends  EntityRepository
{
    public function getCompetitionAnswers($starts_at,$length,$level,User $u){


        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('a.id','a.created',
                'c.id as idcompetition','c.title as competitiontitle','c.level as competitionlevel',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')

            ->from('IkotlinMainBundle:CompetitionAnswer', 'a')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User','u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.idUser = u.id'
            )
            ->leftJoin(
                'Ikotlin\MainBundle\Entity\Competition','c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.idCompetition = c.id'
            )

            ->where("c.level = :level")
            ->andWhere("u.id = :uid")
            ->setParameter("level",$level)
            ->setParameter("uid",$u->getId())
            ->orderBy("a.created", 'DESC')
            ->setFirstResult($starts_at)
            ->setMaxResults($length)
        ;

        //echo $qb;
        return $qb->getQuery()->getResult();
    }

    public function getCompetitionAnswerOptimized($idanswer){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('a.id','a.created','a.content',
                'c.id as idcompetition','c.title as competitiontitle','c.level as competitionlevel',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')
            ->from('IkotlinMainBundle:CompetitionAnswer', 'a')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.idUser = u.id'
            )
            ->leftJoin(
                'Ikotlin\MainBundle\Entity\Competition','c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.idCompetition = c.id'
            )
            ->where("c.id = :idanswer")
            ->setParameter("idanswer",$idanswer)
        ;
        return $qb->getQuery()->getResult();
    }
}