<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Seener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setMessagesSeen($inbox_id = null, $user)
    {
        if (!isset($inbox_id))
            return;
        
        $conn = $this->em->getConnection();

        $q = "UPDATE message SET seen = TRUE WHERE user_inbox_id = ? AND user_id = ? AND seen = FALSE;";
        
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $inbox_id);
        $pst->bindValue(2, $user->getId());

        $pst->execute();    
    }

    public function setNotificationsSeen($group_id = null, $user)
    {
        if (!isset($group_id))
            return;

        $conn = $this->em->getConnection();

        $q = "UPDATE notification SET seen = TRUE WHERE user_group_id = ? AND user_id = ? AND seen = FALSE;";
        
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $group_id);
        $pst->bindValue(2, $user->getId());

        $pst->execute();
    }
}