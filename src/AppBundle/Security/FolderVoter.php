<?php


// src/AppBundle/Security/FolderVoter.php
namespace AppBundle\Security;

use AppBundle\Entity\Folder;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FolderVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Folder) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    { 
           
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }
        
        // you know $subject is a Project object, thanks to supports
        /** @var Project $project */
        $folder = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($folder, $user);
            case self::EDIT:
                return $this->canEdit($folder, $user);
        }
        
        throw new \LogicException($attribute.' This code should not be reached!');
    }
    
    protected function getSupportedAttributes()
    {
        return array('view','edit');
    }
    


    private function canView(Folder $folder, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($project, $user)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$project->isPrivate();
    }

    private function canEdit(Folder $folder, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        
        
        if($user === $folder->getProjectManager()){
            return true;
        }
        // for($member in $folder->getMember()){
        //     if($member == $user){
        //         return true;
        //     }
        // }
        return false;
    }
}