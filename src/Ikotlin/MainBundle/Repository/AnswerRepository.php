<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 20/12/2017
 * Time: 23:21
 */
namespace Ikotlin\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;


class AnswerRepository extends EntityRepository
{
    public function getCommentsByForum($start,$length,$forumid){

        $qb = $this->getEntityManager()->createQueryBuilder();
            $qb
                ->select('a.id','a.content','a.created',
                    'a.rating','u.id as user_id', 'u.username as user_name',
                    'u.picture as user_picture')
                ->from('IKotlin\MainBundle\Entity\Answer', 'a')
                ->leftJoin(
                    'IKotlin\MainBundle\Entity\User',
                    'u',
                    \Doctrine\ORM\Query\Expr\Join::WITH,
                    'a.idUser = u.id'
                )
                ->orderBy('a.rating', 'DESC')
                ->where("a.idForum = :idforum")
                ->setParameter("idforum",$forumid)
                ->setFirstResult($start)
                ->setMaxResults($length)
            ;

        //echo $qb;
        return $qb->getQuery()->getResult();
    }
}