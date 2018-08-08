<?php

namespace App\Models\Db;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Base implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $_table = 'users';
    protected $_modelType = 'App\Models\User';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $_hidden = [
        'password', 'token', 'token_expired'
    ];

    /**
     * Singleton instance
     * @var User
     */
    protected static $_objInstance = NULL;

    /**
     * Get singleton instance
     * @return User
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }

        return self::$_objInstance;
    }

    protected $_map = [
        'id'                => 'id',
        'name'              => 'name',
        'email'             => 'email',
        'password'          => 'password',
        'token'             => 'token',
        'token_expired'     => 'tokenExpired'
    ];

    /**
     * Get user by email
     * @param string $email
     * @return App\Models\User
     */
    public function fetchEntryByEmail($email)
    {
        $query = "SELECT * FROM {$this->_table} WHERE email = :email";

        $params = [
            ':email' => $email
        ];

        return parent::rowToModel($this->fetchRow($query, $params));
    }

    /**
     * Get user by token
     * @param string $token
     * @return App\Models\User
     */
    public function fetchEntryByToken($token)
    {
        $query = "SELECT * FROM {$this->_table} WHERE token = :token";

        $params = [
            ':token' => $token
        ];

        return parent::rowToModel($this->fetchRow($query, $params));
    }

    /**
     * Update user's token
     * @param integer $id
     * @param string $token
     * @param integer $tokenExpired
     * @return boolean
     */
    public function updateToken($id, $token, $tokenExpired)
    {
        $query = "UPDATE {$this->_table} SET token = :token, token_expired = :tokenExpired  WHERE id = :id";

        $params = [
            ':token'            => $token,
            ':tokenExpired'     => date('Y-m-d H:i:s', $tokenExpired),
            ':id'               => $id
        ];

        return $this->execute($query, $params);
    }

    /**
     * Insert or update user
     * @param App\Models\User $user
     * @return App\Models\User
     */
    public function save($user)
    {
        if ($user == null) {
            return null;
        }

        return $user->getId() == null
            ? $this->_insert($user)
            : $this->_update($user);
    }

    /**
     * Create a new user
     * @param App\Models\User $user
     * @return App\Models\User
     */
    private function _insert($user)
    {
        $query = "INSERT INTO {$this->_table} (name, email, password) VALUES (:name, :email, :password)";

        $params = [
            ':name'         => $user->getName(),
            ':email'        => $user->getEmail(),
            ':password'     => $user->getPassword()
        ];

        $this->execute($query, $params);
        $user->setId($this->getLastInsertId());

        return $user;
    }

    /**
     * Update user
     * @param App\Models\User $user
     * @return App\Models\User
     */
    private function _update($user)
    {
        $query = "UPDATE {$this->_table} SET name = :name, email = :email, password = :password  WHERE id = :id";

        $params = [
            ':id'           => $user->getId(),
            ':name'         => $user->getName(),
            ':email'        => $user->getEmail(),
            ':password'     => $user->getPassword()
        ];

        $this->execute($query, $params);

        return $user;
    }
}
