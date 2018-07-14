<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 03/12/2017
 * Time: 15:49
 */
namespace Ikotlin\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;

class forumQuestionRepository extends EntityRepository
{
    public function getForumsBy($start,$length,$searchkey,$orderby){

        $searchOrder="f.rating";

        $qb = $this->getEntityManager()->createQueryBuilder();

        if(!empty($orderby)){
            switch ($orderby){
                case "2":
                    $searchOrder="f.views";
                    break;
                case "3":
                    $searchOrder="f.created";
                    break;
            }
        }

        $searchQuery=trim($searchkey);
        if(empty($searchQuery))
        {
            $qb
                ->select('f.id','f.subject','f.created',
                    'f.rating','f.tags','f.views', 'f.edited as edited',
                    'u.id as user_id', 'u.username as user_name','u.picture as user_picture')
                ->from('IKotlin\MainBundle\Entity\Forum_question', 'f')
                ->leftJoin(
                    'IKotlin\MainBundle\Entity\User',
                    'u',
                    \Doctrine\ORM\Query\Expr\Join::WITH,
                    'f.idUser = u.id'
                )
                ->orderBy($searchOrder, 'DESC')
                ->setFirstResult($start)
                ->setMaxResults($length)
            ;
        }

        else
        {
            $qb
                ->select('f.id','f.subject','f.created',
                    'f.rating','f.tags','f.views','f.edited as edited',
                    'u.id as user_id', 'u.username as user_name','u.picture as user_picture')

                ->from('IKotlin\MainBundle\Entity\Forum_question', 'f')
                ->leftJoin(
                    'IKotlin\MainBundle\Entity\User',
                    'u',
                    \Doctrine\ORM\Query\Expr\Join::WITH,
                    'f.idUser = u.id'
                )

                ->where("LOWER(f.subject) LIKE :query OR LOWER(f.tags) LIKE :query OR LOWER(u.username) LIKE :query")
                ->setParameter("query",'%'.$searchQuery.'%')
                ->orderBy($searchOrder, 'DESC')
                ->setFirstResult($start)
                ->setMaxResults($length)
            ;
        }
        //echo $qb;
        return $qb->getQuery()->getResult();
    }

    public function getForumOptimized($forumid){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('f.id','f.subject','f.content','f.created',
                'f.rating','f.tags','f.views','f.code as code','f.edited as edited',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')
            ->from('IKotlin\MainBundle\Entity\Forum_question', 'f')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'f.idUser = u.id'
            )
            ->where("f.id = :idforum")
            ->setParameter("idforum",$forumid)
        ;
        return $qb->getQuery()->getResult();
    }

    public function getmyForumsOptimized($start,$length,$userid){

        $searchOrder="f.rating";

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('f.id','f.subject','f.created',
                'f.rating','f.tags','f.views', 'f.edited as edited',
                'u.id as user_id', 'u.username as user_name','u.picture as user_picture')
            ->from('IKotlin\MainBundle\Entity\Forum_question', 'f')
            ->leftJoin(
                'IKotlin\MainBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'f.idUser = u.id'
            )
            ->where("f.idUser = :iduser")
            ->setParameter("iduser",$userid)
            ->orderBy($searchOrder, 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($length)
        ;

        //echo $qb;
        return $qb->getQuery()->getResult();
    }



}