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
 * Badge
 *
 * @ORM\Entity(repositoryClass="Ikotlin\MainBundle\Repository\BadgeRepository")
 * @ORM\Table(name="userbadges")
 */

class Badge
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
     * @ORM\Column(name="badgeindic", type="string", length=300, nullable=false)
     */
    private $badgeindic;
    
    
     /**
     * Badge constructor.
     */
    public function __construct()
    {
    }
    
    function getId() {
        return $this->id;
    }

   function getUserid() {
        return $this->userid;
    }

    function setUserid($userid) {
        $this->userid = $userid;
    }
    function getBadgeindic() {
        return $this->badgeindic;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setBadgeindic($badgeindic) {
        $this->badgeindic = $badgeindic;
    }


}