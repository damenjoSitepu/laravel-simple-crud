<?php 
namespace App\Services\Util;

use App\Contracts\RoleServiceContract;
use App\Models\Book;

class RoleService implements RoleServiceContract {
    /**
     * @var array
     */
    private const ROLES = [ 
        "Owner",
        "Member",
        "Co-Owner",
    ];

    /**
     * @var string
     */
    private const OWNER_ROLE = self::ROLES[0];

    /**
     * Get Roles
     *
     * @return array
     */
    public static function getRoles(): array 
    {
        return self::ROLES;
    }

    /**
     * Get Owner Role
     *
     * @return string
     */
    public static function getOwner(): string 
    {
        return self::OWNER_ROLE;
    }

    /**
     * Check Existing Role
     *
     * @param string $role
     * @return boolean
     */
    public static function checkRole(string $role): bool
    {
        try {
            return in_array($role, self::ROLES);
        } catch (\Throwable $t) {
            return false;
        }
    }

    /**
     * Check Owner is already exists in certain book
     *
     * @param Book $book
     * @param string $role
     * @param bool $matchToRoleInput
     * @return boolean
     */
    public static function checkOwnerIsExists(Book $book, string $role, bool $matchToRoleInput = true): bool
    {
        try {
            if ($book->authors->count() === 0) return false;

            $bookAuthors = $book->authors;
            foreach ($bookAuthors as $author) {
                try {
                    $authorRole = $author->pivot->role;
                    if ($matchToRoleInput) {
                        if ($authorRole !== self::getOwner()) continue;
                        // This means owner role is existing!
                        if ($role === $authorRole) return true;
                    } else {
                        if ($authorRole === self::getOwner()) return true;
                    }
                } catch (\Throwable $t) {}
            }
            return false;
        } catch (\Throwable $t) {
            return false;
        }
    }
}