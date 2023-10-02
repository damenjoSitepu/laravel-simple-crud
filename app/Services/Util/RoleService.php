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
    private const ORDER_STATEMENT = "
        CASE 
            WHEN role = 'Owner' THEN 1
            WHEN role = 'Co-Owner' THEN 2 
            WHEN role = 'Member' THEN 3
            ELSE 4
        END
    ";

    /**
     * @var string
     */
    private const OWNER_ROLE = self::ROLES[0];

    /**
     * @var string
     */
    private const MEMBER_ROLE = self::ROLES[1];

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
     * Get Order Statement
     *
     * @return string
     */
    public static function getOrderStatement(): string 
    {
        return self::ORDER_STATEMENT;
    }

    /**
     * Get Member Role
     *
     * @return string
     */
    public static function getMember(): string 
    {
        return self::MEMBER_ROLE;
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