<?php

/**
 * Created by PhpStorm.
 * User: Hatsu
 * Date: 02.07.2017
 * Time: 19:16
 */



class Users extends \Phalcon\Mvc\Collection
{
    public $id;
    public $name;
    public $email;
    public $password;

    public function initialize()
    {
        $this->setSource('users');
    }
}