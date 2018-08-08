<?php

namespace App\Models;

class User extends Base
{
    protected $_id;
    protected $_name;
    protected $_email;
    protected $_password;
    protected $_token;
    protected $_tokenExpired;

    public function getId() { return $this->_id; }
    public function setId($value) { $this->_id = $value; }

    public function getName() { return $this->_name; }
    public function setName($value) { $this->_name = $value; }

    public function getEmail() { return $this->_email; }
    public function setEmail($value) { $this->_email = $value; }

    public function getPassword() { return $this->_password; }
    public function setPassword($value) { $this->_password = $value; }

    public function getToken() { return $this->_token; }
    public function setToken($value) { $this->_token = $value; }

    public function getTokenExpired() { return $this->_tokenExpired; }
    public function setTokenExpired($value) { $this->_tokenExpired = $value; }
}
