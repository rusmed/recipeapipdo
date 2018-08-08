<?php

namespace App\Models;

class Image extends Base
{
    protected $_id;
    protected $_url;

    public function getId() { return $this->_id; }
    public function setId($value) { $this->_id = $value; }

    public function getUrl() { return $this->_url; }
    public function setUrl($value) { $this->_url = $value; }
}
