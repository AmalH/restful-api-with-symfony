<?php
/**
 * Created by PhpStorm.
 * User: Amal
 * Date: 18/11/2017
 * Time: 17:48
 */

namespace Ikotlin\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Forum_question
 *
 * @ORM\Entity(repositoryClass="Ikotlin\MainBundle\Repository\CourseRepository")
 * @ORM\Table(name="usercourses")
 */

class Course
{
    
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id" , onDelete="CASCADE")
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="courseindic", type="string", length=300, nullable=false)
     */
    private $courseindic;

 
    /**
     * Course constructor.
     */
    public function __construct()
    {
       // $this->setCreated(new \DateTime());
        //$this->setViews(0);
        //$this->setRating(0);
    }
    function getUserid() {
        return $this->userid;
    }

    function getCourseindc() {
        return $this->courseindc;
    }

    function setUserid(\User $userid) {
        $this->userid = $userid;
    }

    function setCourseindc($courseindc) {
        $this->courseindc = $courseindc;
    }


}