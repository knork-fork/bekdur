<?php

namespace App\Service;

use App\Entity\UserInbox;
use App\Entity\Message;

class InboxMessageNumber
{

    public function __construct()
    {}

    /*public function calculate(Array $groups, Array $notifications) : Array
    {
        $ret = array();

        foreach ($groups as $group)
        {
            $usergroup = $group->getUserGroup();
            $key = "group_".$usergroup->getId();
            $ret[$key] = $this->getGroupNotificationNumber($usergroup, $notifications);
        }

        return $ret;
    }*/

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

    /*public function getGroupNotificationNumber(UserGroup $group, Array $notifications) : int
    {
        $num = 0;

        foreach ($notifications as $notification)
        {
            if ($notification->getUserGroup()->getId() == $group->getId())
                $num++;
        }

        return $num;
    }*/
}
