<?php

namespace App\Models\Db;

class Image extends Base
{

    protected $_table = 'images';
    protected $_modelType = 'App\Models\Image';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $_hidden = [];

    /**
     * Singleton instance
     * @var Image
     */
    protected static $_objInstance = NULL;

    /**
     * Get singleton instance
     * @return Image
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }

        return self::$_objInstance;
    }

    protected $_map = [
        'id'    => 'id',
        'url'   => 'url'
    ];

    /**
     * Save a new image
     * @param App\Models\Image $image
     * @return App\Models\Image
     */
    public function save($image)
    {
        $query = "INSERT INTO {$this->_table} (url) VALUES (:url)";

        $params = [
            ':url' => $image->getUrl()
        ];

        $this->execute($query, $params);
        $image->setId($this->getLastInsertId());

        return $image;
    }

    /**
     * Get image by id
     * @param integer $id
     * @return App\Models\Image
     */
    public function fetchEntryById($id)
    {
        $query = "SELECT * FROM {$this->_table} WHERE id = :id";

        $params = [
            ':id' => $id
        ];

        return parent::rowToModel($this->fetchRow($query, $params));
    }
}
