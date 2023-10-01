<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AuthorBookServiceContract {
    /**
     * Find Author Books
     *
     * @param integer $authorId
     * @return Collection|bool
     */
    public static function findBooks(int $authorId): Collection | bool;
}