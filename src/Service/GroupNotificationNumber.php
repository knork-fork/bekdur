<?php

namespace App\Service;

use App\Entity\UserGroup;
use App\Entity\Notification;

class GroupNotificationNumber
{

    public function __construct()
    {

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
}
