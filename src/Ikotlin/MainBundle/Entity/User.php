<?php
/**
 * Created by PhpStorm.
 * User: Odil
 * Date: 13/11/2017
 * Time: 21:31
 */
namespace Ikotlin\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"},message="this user already exists")
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string",length=128, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=200, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created",type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastlogged",type="datetime",nullable=true)
     */
    private $lastlogged;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var int
     *
     * @ORM\Column(name="skill_learner", type="integer", nullable=true)
     */
    private $skill_learner;
    /**
     * @var int
     *
     * @ORM\Column(name="skill_challenger", type="integer", nullable=true)
     */
    private $skill_challenger;

    /**
     * @var int
     *
     * @ORM\Column(name="skill_coder", type="integer", nullable=true)
     */
    private $skill_coder;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=200, nullable=true)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean",nullable=true)
     */
    private $confirmed;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setLastlogged(new \DateTime());
        $this->setSkillChallenger(0);
        $this->setSkillCoder(0);
        $this->setSkillLearner(0);
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     * @return \DateTime
     */
    public function getLastlogged()
    {
        return $this->lastlogged;
    }

    /**
     * @param \DateTime $lastlogged
     */
    public function setLastlogged($lastlogged)
    {
        $this->lastlogged = $lastlogged;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getSkillLearner()
    {
        return $this->skill_learner;
    }

    /**
     * @param int $skill_learner
     */
    public function setSkillLearner($skill_learner)
    {
        $this->skill_learner = $skill_learner;
    }

    /**
     * @return int
     */
    public function getSkillChallenger()
    {
        return $this->skill_challenger;
    }

    /**
     * @param int $skill_challenger
     */
    public function setSkillChallenger($skill_challenger)
    {
        $this->skill_challenger = $skill_challenger;
    }

    /**
     * @return int
     */
    public function getSkillCoder()
    {
        return $this->skill_coder;
    }

    /**
     * @param int $skill_coder
     */
    public function setSkillCoder($skill_coder)
    {
        $this->skill_coder = $skill_coder;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

}