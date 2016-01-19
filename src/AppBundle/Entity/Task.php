<?php

// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
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
     * @ORM\Column(type="string", length=30)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="tasks")
     */
    private $folder;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tasks")
     */
    private $tags;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="task")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="assignedTo")
     */
    private $assignee;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followerOf")
     * )
     */
    private $follower;
    
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
    
    /**
     * @ORM\Column(type="datetime")
    */
    private $startDate;
    
    /**
     * @ORM\Column(type="datetime")
    */
    private $dueDate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
    */
    private $endDate;

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

    
    public function canView(User $user){
        
        if($this->canEdit($user)){
            return true;
        }
        
        return $user == $this->assignee;
    }
    
    public function canEdit(User $user){
        return $user == $this->assignee;
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
     * @return Task
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
     * @return Task
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
     * Set status
     *
     * @param string $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Task
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
     * @return Task
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Task
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set folder
     *
     * @param \AppBundle\Entity\Folder $folder
     *
     * @return Task
     */
    public function setFolder(\AppBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \AppBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Add tag
     *
     * @param \AppBundle\Entity\Tag $tag
     *
     * @return Task
     */
    public function addTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Task
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set assignee
     *
     * @param \AppBundle\Entity\User $assignee
     *
     * @return Task
     */
    public function setAssignee(\AppBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \AppBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add follower
     *
     * @param \AppBundle\Entity\User $follower
     *
     * @return Task
     */
    public function addFollower(\AppBundle\Entity\User $follower)
    {
        $this->follower[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\User $follower
     */
    public function removeFollower(\AppBundle\Entity\User $follower)
    {
        $this->follower->removeElement($follower);
    }

    /**
     * Get follower
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Task
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
