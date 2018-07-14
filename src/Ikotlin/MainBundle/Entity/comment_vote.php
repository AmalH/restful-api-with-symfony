<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 22/12/2017
 * Time: 13:56
 */

namespace Ikotlin\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Forum_question
 *
 * @ORM\Entity()
 * @ORM\Table(name="comment_vote")
 */

class comment_vote
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
     * @var \Answer
     * @ORM\ManyToOne(targetEntity="Ikotlin\MainBundle\Entity\Answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comment", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $idComment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vote", type="boolean", nullable=true)
     */
    private $vote;

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
     * @return bool
     */
    public function isVote()
    {
        return $this->vote;
    }

    /**
     * @param bool $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return \Answer
     */
    public function getIdComment()
    {
        return $this->idComment;
    }

    /**
     * @param \Answer $idComment
     */
    public function setIdComment($idComment)
    {
        $this->idComment = $idComment;
    }

}