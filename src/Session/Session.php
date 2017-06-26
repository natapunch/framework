<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 11.06.17
 * Time: 19:03
 */

namespace Punchenko\Framework\Session;


class Session
{
    /**
     * class Session as Singleton
     *
     * @var null    Instance container
     */
    protected static $instance = null;
    /**
     * Session constructor
     */
    protected function __construct(){
        session_start();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): self{
        //check if initialized
        if (self::$instance == null) {
            //init
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *  getter
     *
     * @param $varname
     *
     * @return string
     */
    public function get($name){
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
    /**
     *  setter
     *
     * @param $varname
     * @param $value
     */
    public function set(string $name, $value){
        $_SESSION[$name] = $value;
    }
    private function __clone(){
    // Empty
}
public function clearAll()
{
    session_unset();
}
}