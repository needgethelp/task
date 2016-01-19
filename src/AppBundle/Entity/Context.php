<?php

// src/AppBundle/Entity/Context.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="app_context")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ContextRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Context
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="context")
     */
    private $folders;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="contexts")
     */
    private $members;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="managerOfContexts")
     */
    private $managers;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="activeContext")
     */
    private $activeContextUser; 
    
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="creatorOfTask")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime")
    */
    private $changedAt;

    public function __construct()
    {
        $this->status = "created";
        $this->createdAt = new \DateTime("now");
        $this->changedAt = new \DateTime("now");
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
     * Set title
     *
     * @param string $title
     *
     * @return Context
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
     * @return Context
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
     * @return Context
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
     * @return Context
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
     * Add folder
     *
     * @param \AppBundle\Entity\Folder $folder
     *
     * @return Context
     */
    public function addFolder(\AppBundle\Entity\Folder $folder)
    {
        $this->folders[] = $folder;

        return $this;
    }

    /**
     * Remove folder
     *
     * @param \AppBundle\Entity\Folder $folder
     */
    public function removeFolder(\AppBundle\Entity\Folder $folder)
    {
        $this->folders->removeElement($folder);
    }

    /**
     * Get folders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Add member
     *
     * @param \AppBundle\Entity\User $member
     *
     * @return Context
     */
    public function addMember(\AppBundle\Entity\User $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \AppBundle\Entity\User $member
     */
    public function removeMember(\AppBundle\Entity\User $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add manager
     *
     * @param \AppBundle\Entity\User $manager
     *
     * @return Context
     */
    public function addManager(\AppBundle\Entity\User $manager)
    {
        $this->managers[] = $manager;

        return $this;
    }

    /**
     * Remove manager
     *
     * @param \AppBundle\Entity\User $manager
     */
    public function removeManager(\AppBundle\Entity\User $manager)
    {
        $this->managers->removeElement($manager);
    }

    /**
     * Get managers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Add activeContextUser
     *
     * @param \AppBundle\Entity\User $activeContextUser
     *
     * @return Context
     */
    public function addActiveContextUser(\AppBundle\Entity\User $activeContextUser)
    {
        $this->activeContextUser[] = $activeContextUser;

        return $this;
    }

    /**
     * Remove activeContextUser
     *
     * @param \AppBundle\Entity\User $activeContextUser
     */
    public function removeActiveContextUser(\AppBundle\Entity\User $activeContextUser)
    {
        $this->activeContextUser->removeElement($activeContextUser);
    }

    /**
     * Get activeContextUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActiveContextUser()
    {
        return $this->activeContextUser;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Context
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
}
