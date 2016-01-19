<?php

// src/AppBundle/Entity/Folder.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="folders")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FolderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Folder
{
    const ACCESS_PRIVATE = 0;
    const ACCESS_PROTECTED = 1;
    const ACCESS_PUBLIC = 2;
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
        /**
     * @ORM\Column(type="integer")
     */
    private $visibility;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="creatorOfFolder")
     */
    private $createdBy;
    
    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="privateFolder")
     */
    private $privateFolderOf;
    
    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="folder")
     */
    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="memberOfFolders")
     */
    private $member;
    
    /**
     * @ORM\ManyToOne(targetEntity="Context", inversedBy="folders")
     */
    private $context;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="managerOfFolders")
     */
    private $manager;

    /**
     * @ORM\Column(type="datetime")
    */
    private $changedAt;

    public function __construct()
    {
        $this->status = "created";
        $this->createdAt = new \DateTime("now");
        $this->changedAt = new \DateTime("now");
        $this->setVisibility = 2;
    }
    
    public function setPrivate($user)
    {
        $this->status = "created";
        $this->title = 'Private Project ' . $user->getUsername();
        $this->description = 'Private Project of' . $user->getUsername();
        $this->createdAt = new \DateTime("now");
        $this->changedAt = new \DateTime("now");
        $this->visibility = 0;
        $this->manager = $user;
        $this->privateFolderOf = $user;
        $this->createdBy = $user;
    }
    
    /** 
     *  @ORM\PrePersist 
     */
    public function doStuffOnPrePersist()
    {
        $this->createdAt = new \DateTime("now");
        $this->changedAt = new \DateTime("now");
    }
    


    /**
     * @ORM\PreUpdate
     */
    public function doStuffOnPreUpdate()
    {
        $this->changedAt = new \DateTime("now");
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visibility
     *
     * @param integer $visibility
     *
     * @return Folder
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return integer
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Folder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Folder
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Folder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set changedAt
     *
     * @param \DateTime $changedAt
     *
     * @return Folder
     */
    public function setChangedAt($changedAt)
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    /**
     * Get changedAt
     *
     * @return \DateTime
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Folder
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set privateFolderOf
     *
     * @param \AppBundle\Entity\User $privateFolderOf
     *
     * @return Folder
     */
    public function setPrivateFolderOf(\AppBundle\Entity\User $privateFolderOf = null)
    {
        $this->privateFolderOf = $privateFolderOf;

        return $this;
    }

    /**
     * Get privateFolderOf
     *
     * @return \AppBundle\Entity\User
     */
    public function getPrivateFolderOf()
    {
        return $this->privateFolderOf;
    }

    /**
     * Add task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return Folder
     */
    public function addTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \AppBundle\Entity\Task $task
     */
    public function removeTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add member
     *
     * @param \AppBundle\Entity\User $member
     *
     * @return Folder
     */
    public function addMember(\AppBundle\Entity\User $member)
    {
        $this->member[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \AppBundle\Entity\User $member
     */
    public function removeMember(\AppBundle\Entity\User $member)
    {
        $this->member->removeElement($member);
    }

    /**
     * Get member
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set context
     *
     * @param \AppBundle\Entity\Context $context
     *
     * @return Folder
     */
    public function setContext(\AppBundle\Entity\Context $context = null)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return \AppBundle\Entity\Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set manager
     *
     * @param \AppBundle\Entity\User $manager
     *
     * @return Folder
     */
    public function setManager(\AppBundle\Entity\User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \AppBundle\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }
}
