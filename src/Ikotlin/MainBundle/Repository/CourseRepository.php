<?php
/**
 * Created by PhpStorm.
 * User: Amal
 * Date: 03/12/2017
 * Time: 15:49
 */
namespace Ikotlin\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;

class CourseRepository extends EntityRepository
{

    public function getUserCourses($id){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('IDENTITY(f.userid)','f.courseindic','f.finishedchapters','f.earnedbadges')
            ->from('IKotlin\MainBundle\Entity\Course', 'f')
            ->where("f.userid = :id")
            ->setParameter("id",$id)
        ;
        return $qb->getQuery()->getResult();
    }
    
    public function isHasCourse($id,$courseindic){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('IDENTITY(f.userid)','f.courseindic')
            ->from('IKotlin\MainBundle\Entity\Course', 'f')
            ->where("f.userid = :id AND f.courseindic = :courseindic")
            ->setParameter("id",$id)
            ->setParameter("courseindic",$courseindic)
        ;
        return $qb->getQuery()->getResult();
    }
  
}