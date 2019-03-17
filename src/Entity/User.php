<?php

namespace App\Entity;

class User
{
    public $id;
    public $name;
    // to-do: add roles, email, login token(??) etc.

    function __construct()
    {
        // Hardcoded values for testing
        $this->id = 0;
        $this->name = "default_user";
    }
}
