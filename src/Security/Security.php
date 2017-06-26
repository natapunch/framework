<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 11.06.17
 * Time: 19:59
 */

namespace Punchenko\Framework\Security;


use Punchenko\Framework\Config\Config;
use Punchenko\Framework\DI\DInjector;

class Security
{
    protected $session;

    protected $config;

    protected static $user;
    /**
     * Security constructor.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->session = DInjector::make('Session');
        $this->getUser();
    }
    /**
     * Get current user object
     */
    public function getUser(): array
    {
        if(empty(self::$user))
        {
            $user = DInjector::make('Punchenko\Framework\Security\UserInterface');
            forech ($user as $user_id=>$value)
            {
                $this->session->set($this->varname[$user_id],$value);
            }
            self::$user = $user;
        }
        return self::$user;
    }
    /**
     * Check current authorization status
     *
     * @return bool
     */
    public function checkAuth(): bool
    {
        return !self::$user->isGuest();
    }
    
    /**
     * Check permission
     *
     * @param $permission_name
     * @param array ...$args
     * @return bool
     */
    public function checkPermission($permission_name, ...$args): bool
    {
        $allow = true;
        if($permissions = $this->config->permissions){
            if(isset($permissions[$permission_name])){
                $accessor = $permissions[$permission_name];
                if(is_bool($accessor)){
                    $allow = $accessor;
                } elseif(is_string($accessor)){
                    // Resolve rule:
                    $parts = explode('@', $accessor);
                    $resolver_class = array_shift($parts);
                    $resolver_method = array_shift($parts);
                    $resolver = DInjector::make($resolver_class);
                    if(!empty($resolver)){
                        $reflection_class = new \ReflectionClass($resolver);
                        if($reflection_class->hasMethod($resolver_method)){
                            $method = $reflection_class->getMethod($resolver_method);
                            $allow = $method->invokeArgs($resolver, $args);
                        }
                    }
                }
            }
        }
        return (bool)$allow;
    }

    /**
     * Authorize user
     * @param UserInterface $user
     */
    public function authorize(UserInterface $user){
        $this->session->user_id = $user;
        $this->getUser();
    }

    /**
     * Call statically
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = DInjector::make('Security');
        return call_user_func_array([$instance, $name], $arguments);
    }
}