<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 18/11/2017
 * Time: 17:57
 */

namespace Ikotlin\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Forum_question
 *
 * @ORM\Entity(repositoryClass="Ikotlin\MainBundle\Repository\AnswerRepository")
 * @ORM\Table(name="forum_answer")
 */

class Answer
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
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=700, nullable=false)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created",type="datetime")
     */
    private $created;

    /**
     * Answer constructor.
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setRating(0);
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

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}