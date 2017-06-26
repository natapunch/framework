<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 15.06.17
 * Time: 13:15
 */

namespace Punchenko\Framework\Model;


use Punchenko\Framework\Request\Request;
use Punchenko\Framework\Security\UserInterface;

class Users extends Model implements UserInterface
{
    public $table = 'user';
    /**
     * @inheritdoc
     */
    public function isGuest(): bool
    {
       
            return !(bool)$this->id_user;
    }
    
    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        $roles = explode(',', $this->roles);
        return (array)$roles;
    }
    /**
     * Try to check if can be authorized with request data
     *
     * @param Request $request
     * @return bool
     */
    public function checkCredentials(Request $request): bool
    {
        return (($this->login === $request->login) && (md5($request->password) === $this->password));
    }

   

    /**
     * Проверяет имя: не меньше, чем 2 символа
     * @param string $name <p>Имя</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }


    /**
     * Проверяет имя: не меньше, чем 6 символов
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    
    



}