<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 11.06.17
 * Time: 20:17
 */

namespace Punchenko\Framework\Security;


use Punchenko\Framework\Request\Request;

interface UserInterface
{
    /**
     * Check if user is a guest
     *
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * Get user roles
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * @param Request $request
     * @return bool
     */
    public function checkCredentials(Request $request): bool;
}