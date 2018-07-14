<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 11/01/2018
 * Time: 00:45
 */

namespace Ikotlin\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Competition_Answer
 *
 * @ORM\Entity(repositoryClass="Ikotlin\MainBundle\Repository\Competition_answerRepository")
 * @ORM\Table(name="competition_answer")
 */

class Competition_Answer
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
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=700, nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created",type="datetime")
     */
    private $created;

    /**
     * @var \Competition
     *
     * @ORM\ManyToOne(targetEntity="Ikotlin\MainBundle\Entity\Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_competition", referencedColumnName="id" , onDelete="SET NULL")
     * })
     */
    private $idCompetition;

    /**
     * Competition_Answer constructor.
     */
    public function __construct()
    {
        $this->created=new \DateTime();
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

    /**
     * @return \Competition
     */
    public function getIdCompetition()
    {
        return $this->idCompetition;
    }

    /**
     * @param \Competition $idCompetition
     */
    public function setIdCompetition($idCompetition)
    {
        $this->idCompetition = $idCompetition;
    }


}