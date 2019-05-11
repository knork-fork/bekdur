<?php

namespace App\Service;

use App\Entity\UserInbox;
use App\Entity\Message;

class InboxMessageNumber
{

    public function __construct()
    {}

    public function calculate(Array $inboxes, Array $messages) : Array
    {
        $ret = array();

        foreach ($inboxes as $inbox)
        {
            $userinbox = $inbox->getUserInbox();
            $key = "inbox_".$userinbox->getId();
            $ret[$key] = $this->getInboxMessageNumber($userinbox, $messages);
        }

        return $ret;
    }

    public function setInboxMessageNumber(UserInbox $inbox, Array $messages)
    {
        $num = 0;

        foreach ($messages as $message)
        {
            if ($message->getUserInbox()->getId() == $inbox->getId())
                $num++;
        }

        $inbox->setMessageNumber($num);
    }

    public function getInboxMessageNumber(UserInbox $inbox, Array $messages) : int
    {
        $num = 0;

        foreach ($messages as $message)
        {
            if ($message->getUserInbox()->getId() == $inbox->getId())
                $num++;
        }

        return $num;
    }
}
