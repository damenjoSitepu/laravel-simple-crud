<?php 
namespace App\Contracts;

use App\Models\Book;

interface RoleServiceContract {
    /**
     * Get Roles
     *
     * @return array
     */
    public static function getRoles(): array;

    /**
     * Get Owner Role
     *
     * @return string
     */
    public static function getOwner(): string;

    /**
     * Check Existing Role
     *
     * @param string $role
     * @return boolean
     */
    public static function checkRole(string $role): bool;

    /**
     * Check Owner is already exists in certain book
     *
     * @param Book $book
     * @param string $role
     * @param bool $matchToRoleInput
     * @return boolean
     */
    public static function checkOwnerIsExists(Book $book, string $role, bool $matchToRoleInput): bool;
}