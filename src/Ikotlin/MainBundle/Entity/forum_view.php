<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 22/12/2017
 * Time: 13:55
 */

namespace Ikotlin\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Forum_question
 *
 * @ORM\Entity()
 * @ORM\Table(name="forum_view")
 */

class forum_view
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
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $idUser;

    /**
     * @var \Forum_question
     *
     * @ORM\ManyToOne(targetEntity="Forum_question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_forum", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $idForum;

    /**
     * forum_vote constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param \User $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return \Forum_question
     */
    public function getIdForum()
    {
        return $this->idForum;
    }

    /**
     * @param \Forum_question $idForum
     */
    public function setIdForum($idForum)
    {
        $this->idForum = $idForum;
    }
}