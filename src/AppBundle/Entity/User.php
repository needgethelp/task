<?php

// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
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
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $firstname;
    
        /**
     * @ORM\Column(type="string", length=25)
     */
    private $lastname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array("ROLE_USER");

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime")
    */
    private $changedAt;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="assignee")
     */
    private $assignedTo;
    
    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="createdBy")
     */
    private $creatorOfTask;
    
    /**
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="createdBy")
     */
    private $creatorOfFolder;
    
        /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="createdBy")
     */
    private $creatorOfTag;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="createdBy")
     */
    private $creatorOfComment;
    
    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="owner")
     */
    private $ownerOfTag;
    
    /**
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="manager")
     */
    private $managerOfFolders;
    
    /**
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="follower")
     */
    private $followerOf;
    
    /**
     * @ORM\ManyToMany(targetEntity="Folder", mappedBy="member")
     */
    private $memberOfFolders;
    
    /**
     * @ORM\OneToOne(targetEntity="Folder", inversedBy="privateFolderOf")
     */
    private $privateFolder;
    
    /**
     * @ORM\ManyToMany(targetEntity="Context", inversedBy="managers")
     */
    private $managerOfContexts;
    
     /**
     * @ORM\ManyToOne(targetEntity="Context", inversedBy="activeContextUser")
     */
    private $activeContext;   

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
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
     * @ORM\PreRemove
     */
    public function doStuffOnPreRemove()
    {

    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles = 'ROLE_USER';
        }

        return array_unique($roles);
    }
    
   

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }


    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Add assignedTo
     *
     * @param \AppBundle\Entity\Task $assignedTo
     *
     * @return User
     */
    public function addAssignedTo(\AppBundle\Entity\Task $assignedTo)
    {
        $this->assignedTo[] = $assignedTo;

        return $this;
    }

    /**
     * Remove assignedTo
     *
     * @param \AppBundle\Entity\Task $assignedTo
     */
    public function removeAssignedTo(\AppBundle\Entity\Task $assignedTo)
    {
        $this->assignedTo->removeElement($assignedTo);
    }

    /**
     * Get assignedTo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Add creatorOfTask
     *
     * @param \AppBundle\Entity\Task $creatorOfTask
     *
     * @return User
     */
    public function addCreatorOfTask(\AppBundle\Entity\Task $creatorOfTask)
    {
        $this->creatorOfTask[] = $creatorOfTask;

        return $this;
    }

    /**
     * Remove creatorOfTask
     *
     * @param \AppBundle\Entity\Task $creatorOfTask
     */
    public function removeCreatorOfTask(\AppBundle\Entity\Task $creatorOfTask)
    {
        $this->creatorOfTask->removeElement($creatorOfTask);
    }

    /**
     * Get creatorOfTask
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatorOfTask()
    {
        return $this->creatorOfTask;
    }

    /**
     * Add creatorOfFolder
     *
     * @param \AppBundle\Entity\Folder $creatorOfFolder
     *
     * @return User
     */
    public function addCreatorOfFolder(\AppBundle\Entity\Folder $creatorOfFolder)
    {
        $this->creatorOfFolder[] = $creatorOfFolder;

        return $this;
    }

    /**
     * Remove creatorOfFolder
     *
     * @param \AppBundle\Entity\Folder $creatorOfFolder
     */
    public function removeCreatorOfFolder(\AppBundle\Entity\Folder $creatorOfFolder)
    {
        $this->creatorOfFolder->removeElement($creatorOfFolder);
    }

    /**
     * Get creatorOfFolder
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatorOfFolder()
    {
        return $this->creatorOfFolder;
    }

    /**
     * Add creatorOfTag
     *
     * @param \AppBundle\Entity\Tag $creatorOfTag
     *
     * @return User
     */
    public function addCreatorOfTag(\AppBundle\Entity\Tag $creatorOfTag)
    {
        $this->creatorOfTag[] = $creatorOfTag;

        return $this;
    }

    /**
     * Remove creatorOfTag
     *
     * @param \AppBundle\Entity\Tag $creatorOfTag
     */
    public function removeCreatorOfTag(\AppBundle\Entity\Tag $creatorOfTag)
    {
        $this->creatorOfTag->removeElement($creatorOfTag);
    }

    /**
     * Get creatorOfTag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatorOfTag()
    {
        return $this->creatorOfTag;
    }

    /**
     * Add creatorOfComment
     *
     * @param \AppBundle\Entity\Comment $creatorOfComment
     *
     * @return User
     */
    public function addCreatorOfComment(\AppBundle\Entity\Comment $creatorOfComment)
    {
        $this->creatorOfComment[] = $creatorOfComment;

        return $this;
    }

    /**
     * Remove creatorOfComment
     *
     * @param \AppBundle\Entity\Comment $creatorOfComment
     */
    public function removeCreatorOfComment(\AppBundle\Entity\Comment $creatorOfComment)
    {
        $this->creatorOfComment->removeElement($creatorOfComment);
    }

    /**
     * Get creatorOfComment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatorOfComment()
    {
        return $this->creatorOfComment;
    }

    /**
     * Add ownerOfTag
     *
     * @param \AppBundle\Entity\Tag $ownerOfTag
     *
     * @return User
     */
    public function addOwnerOfTag(\AppBundle\Entity\Tag $ownerOfTag)
    {
        $this->ownerOfTag[] = $ownerOfTag;

        return $this;
    }

    /**
     * Remove ownerOfTag
     *
     * @param \AppBundle\Entity\Tag $ownerOfTag
     */
    public function removeOwnerOfTag(\AppBundle\Entity\Tag $ownerOfTag)
    {
        $this->ownerOfTag->removeElement($ownerOfTag);
    }

    /**
     * Get ownerOfTag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnerOfTag()
    {
        return $this->ownerOfTag;
    }

    /**
     * Add managerOfFolder
     *
     * @param \AppBundle\Entity\Folder $managerOfFolder
     *
     * @return User
     */
    public function addManagerOfFolder(\AppBundle\Entity\Folder $managerOfFolder)
    {
        $this->managerOfFolders[] = $managerOfFolder;

        return $this;
    }

    /**
     * Remove managerOfFolder
     *
     * @param \AppBundle\Entity\Folder $managerOfFolder
     */
    public function removeManagerOfFolder(\AppBundle\Entity\Folder $managerOfFolder)
    {
        $this->managerOfFolders->removeElement($managerOfFolder);
    }

    /**
     * Get managerOfFolders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagerOfFolders()
    {
        return $this->managerOfFolders;
    }

    /**
     * Add followerOf
     *
     * @param \AppBundle\Entity\Task $followerOf
     *
     * @return User
     */
    public function addFollowerOf(\AppBundle\Entity\Task $followerOf)
    {
        $this->followerOf[] = $followerOf;

        return $this;
    }

    /**
     * Remove followerOf
     *
     * @param \AppBundle\Entity\Task $followerOf
     */
    public function removeFollowerOf(\AppBundle\Entity\Task $followerOf)
    {
        $this->followerOf->removeElement($followerOf);
    }

    /**
     * Get followerOf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowerOf()
    {
        return $this->followerOf;
    }

    /**
     * Add memberOfFolder
     *
     * @param \AppBundle\Entity\Folder $memberOfFolder
     *
     * @return User
     */
    public function addMemberOfFolder(\AppBundle\Entity\Folder $memberOfFolder)
    {
        $this->memberOfFolders[] = $memberOfFolder;

        return $this;
    }

    /**
     * Remove memberOfFolder
     *
     * @param \AppBundle\Entity\Folder $memberOfFolder
     */
    public function removeMemberOfFolder(\AppBundle\Entity\Folder $memberOfFolder)
    {
        $this->memberOfFolders->removeElement($memberOfFolder);
    }

    /**
     * Get memberOfFolders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberOfFolders()
    {
        return $this->memberOfFolders;
    }

    /**
     * Set privateFolder
     *
     * @param \AppBundle\Entity\Folder $privateFolder
     *
     * @return User
     */
    public function setPrivateFolder(\AppBundle\Entity\Folder $privateFolder = null)
    {
        $this->privateFolder = $privateFolder;

        return $this;
    }

    /**
     * Get privateFolder
     *
     * @return \AppBundle\Entity\Folder
     */
    public function getPrivateFolder()
    {
        return $this->privateFolder;
    }

    /**
     * Add managerOfContext
     *
     * @param \AppBundle\Entity\Context $managerOfContext
     *
     * @return User
     */
    public function addManagerOfContext(\AppBundle\Entity\Context $managerOfContext)
    {
        $this->managerOfContexts[] = $managerOfContext;

        return $this;
    }

    /**
     * Remove managerOfContext
     *
     * @param \AppBundle\Entity\Context $managerOfContext
     */
    public function removeManagerOfContext(\AppBundle\Entity\Context $managerOfContext)
    {
        $this->managerOfContexts->removeElement($managerOfContext);
    }

    /**
     * Get managerOfContexts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagerOfContexts()
    {
        return $this->managerOfContexts;
    }

    /**
     * Set activeContext
     *
     * @param \AppBundle\Entity\Context $activeContext
     *
     * @return User
     */
    public function setActiveContext(\AppBundle\Entity\Context $activeContext = null)
    {
        $this->activeContext = $activeContext;

        return $this;
    }

    /**
     * Get activeContext
     *
     * @return \AppBundle\Entity\Context
     */
    public function getActiveContext()
    {
        return $this->activeContext;
    }
}
