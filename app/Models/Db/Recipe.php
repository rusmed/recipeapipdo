<?php

namespace App\Models\Db;

class Recipe extends Base
{

    protected $_table = 'recipes';
    protected $_modelType = 'App\Models\Recipe';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $_hidden = [
        'author_id', 'image_id'
    ];

    /**
     * Singleton instance
     * @var Recipe
     */
    protected static $_objInstance = NULL;

    /**
     * Get singleton instance
     * @return Recipe
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }

        return self::$_objInstance;
    }

    protected $_map = [
        'id'            => 'id',
        'title'         => 'title',
        'body'          => 'body',
        'author_id'     => 'authorId',
        'image_id'      => 'imageId'
    ];

    /**
     * Insert or update recipe
     * @param App\Models\Recipe $recipe
     * @return App\Models\Recipe
     */
    public function save($recipe)
    {
        if ($recipe == null) {
            return null;
        }

        return $recipe->getId() == null
            ? $this->_insert($recipe)
            : $this->_update($recipe);
    }

    /**
     * Create a new recipe
     * @param App\Models\Recipe $recipe
     * @return App\Models\Recipe
     */
    private function _insert($recipe)
    {
        $query = "INSERT INTO {$this->_table} (title, body, author_id, image_id) VALUES (:title, :body, :authorId, :imageId)";

        $params = [
            ':title'        => $recipe->getTitle(),
            ':body'         => $recipe->getBody(),
            ':authorId'     => $recipe->getAuthorId(),
            ':imageId'      => $recipe->getImageId()
        ];

        $this->execute($query, $params);
        $recipe->setId($this->getLastInsertId());

        return $recipe;
    }

    /**
     * Update recipe
     * @param App\Models\Recipe $recipe
     * @return App\Models\Recipe
     */
    private function _update($recipe)
    {
        $query = "UPDATE {$this->_table} SET title = :title, body = :body, image_id = :imageId WHERE id = :id";

        $params = [
            ':id'       => $recipe->getId(),
            ':title'    => $recipe->getTitle(),
            ':body'     => $recipe->getBody(),
            ':imageId'  => $recipe->getImageId()
        ];

        $this->execute($query, $params);

        return $recipe;
    }

    /**
     * Get recipe by id
     * @param integer $id
     * @return App\Models\Recipe
     */
    public function fetchEntryById($id)
    {
        $query = "SELECT r.id, r.title, r.body, r.author_id, r.image_id, u.name, u.email, i.url FROM {$this->_table} AS r";
        $query .= " INNER JOIN images i ON i.id = r.image_id";
        $query .= " INNER JOIN users u ON u.id = r.author_id";
        $query .= " WHERE r.id = :id";

        $params = [
            ':id' => $id
        ];

        return $this->_relRowToModel($this->fetchRow($query, $params));
    }

    /**
     * Get list recipes
     * @return App\Models\Recipe[]
     */
    public function fetchEntries()
    {
        $query = "SELECT r.id, r.title, r.body, r.author_id, r.image_id, u.name, u.email, i.url FROM {$this->_table} AS r";
        $query .= " INNER JOIN images i ON i.id = r.image_id";
        $query .= " INNER JOIN users u ON u.id = r.author_id";

        $params = [];

        return array_map(array($this, '_relRowToModel'), $this->fetchAll($query, $params));
    }

    /**
     * Delete recipe by id
     * @param integer $id
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->_table} WHERE id = :id";

        $params = [
            ':id' => $id
        ];

        $this->execute($query, $params);
    }

    /**
     * maps db row to object
     * @param array $data
     * @return App\Models\Recipe
     */
    protected function _relRowToModel($data)
    {
        if (!$data) return null;

        $image = new \App\Models\Image();
        $image->setId($data['image_id']);
        $image->setUrl($data['url']);

        $author = new \App\Models\User();
        $author->setId($data['author_id']);
        $author->setName($data['name']);
        $author->setEmail($data['email']);

        $recipe = new \App\Models\Recipe();
        $recipe->setId($data['id']);
        $recipe->setTitle($data['title']);
        $recipe->setBody($data['body']);
        $recipe->setAuthorId($data['author_id']);
        $recipe->setImageId($data['image_id']);
        $recipe->setAuthor($author);
        $recipe->setImage($image);

        return $recipe;
    }

    /**
     * convert model with relations to array for response
     * @param App\Models\Recipe $recipe
     * @return array
     */
    public function relModelToArray($recipe)
    {
        if (!$recipe) return null;

        $dbRecipe = new Recipe();
        $result = $dbRecipe->modelToArray($recipe);

        $result['image'] = (new Image)->modelToArray($recipe->getImage());
        $result['author'] = (new User)->modelToArray($recipe->getAuthor());

        return $result;
    }

    /**
     * convert models with relations to array for response
     * @param Recipe[] $recipe
     * @return array
     */
    public function relModelsToArray(array $recipes)
    {
        return array_map(['self','relModelToArray'], $recipes);
    }
}
