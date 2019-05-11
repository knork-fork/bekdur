<?php

namespace App\Service;

use App\Entity\UserGroup;
use App\Entity\Notification;

class GroupNotificationNumber
{

    public function __construct()
    {}

    public function calculate(Array $groups, Array $notifications) : Array
    {
        $ret = array();

        foreach ($groups as $group)
        {
            $usergroup = $group->getUserGroup();
            $key = "group_".$usergroup->getId();
            $ret[$key] = $this->getGroupNotificationNumber($usergroup, $notifications);
        }

        return $ret;
    }

    public function setGroupNotificationNumber(UserGroup $group, Array $notifications)
    {
        $num = 0;

        foreach ($notifications as $notification)
        {
            if ($notification->getUserGroup()->getId() == $group->getId())
                $num++;
        }

        $group->setNotificationNumber($num);
    }

    public function getGroupNotificationNumber(UserGroup $group, Array $notifications) : int
    {
        $num = 0;

        foreach ($notifications as $notification)
        {
            if ($notification->getUserGroup()->getId() == $group->getId())
                $num++;
        }

        return $num;
    }
}
