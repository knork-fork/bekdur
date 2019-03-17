<?php

// This is a test/example file, delete later

namespace App\Entity;

class User_old
{
    public $id;
    public $name;
    // to-do: add roles, email, login token(??) etc.

    function __construct()
    {
        // Get user with raw query
        /*echo 'Testing db...<br>';

        $em = $this->getDoctrine()->getManager();
        $q = "SELECT * FROM test_table WHERE name = :name;";

        $statement = $em->getConnection()->prepare($q);
        $statement->bindValue("name", "Duje");
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row)
        {
            echo $row["name"].'<br>';
        }

        echo 'Test ok!<br>';

        $user = new User();*/


        // Hardcoded values for testing
        $this->id = 0;
        $this->name = "default_user";
    }
}
