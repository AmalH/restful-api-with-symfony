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
 * ForumQuestion
 *
 * @ORM\Entity(repositoryClass="Ikotlin\MainBundle\Repository\CourseRepository")
 * @ORM\Table(name="usercourses")
 */

class Course
{
   
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="courseindic", type="string", length=300, nullable=false)
     */
    private $courseindic;
    
    /**
     * @var string
     *
     * @ORM\Column(name="finishedchapters", type="string", length=300, nullable=true, options={"default":"0"})
     */
    private $finishedchapters;
    
    /**
     * @var string
     *
     * @ORM\Column(name="earnedbadges", type="string", length=300, nullable=true, options={"default":"0"})
     */
    private $earnedbadges;


 
    /**
     * Course constructor.
     */
    public function __construct()
    {
    }
  
    function getCourseindic() {
        return $this->courseindic;
    }

    function setCourseindic($courseindic) {
        $this->courseindic = $courseindic;
    }

        
    function getUserid() {
        return $this->userid;
    }

    function setUserid($userid) {
        $this->userid = $userid;
    }

    function getFinishedchapters() {
        return $this->finishedchapters;
    }

    function getEarnedbadges() {
        return $this->earnedbadges;
    }

    function setFinishedchapters($finishedchapters) {
        $this->finishedchapters = $finishedchapters;
    }

    function setEarnedbadges($earnedbadges) {
        $this->earnedbadges = $earnedbadges;
    }





}