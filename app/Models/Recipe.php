<?php

namespace App\Models;

class Recipe extends Base
{
    protected $_id;
    protected $_title;
    protected $_body;
    protected $_authorId;
    protected $_imageId;

    protected $_author;
    protected $_image;

    public function getId() { return $this->_id; }
    public function setId($value) { $this->_id = $value; }

    public function getTitle() { return $this->_title; }
    public function setTitle($value) { $this->_title = $value; }

    public function getBody() { return $this->_body; }
    public function setBody($value) { $this->_body = $value; }

    public function getAuthorId() { return $this->_authorId; }
    public function setAuthorId($value) { $this->_authorId = $value; }

    public function getImageId() { return $this->_imageId; }
    public function setImageId($value) { $this->_imageId = $value; }

    public function getAuthor() { return $this->_author; }
    public function setAuthor($value) { $this->_author = $value; }

    public function getImage() { return $this->_image; }
    public function setImage($value) { $this->_image = $value; }
}
