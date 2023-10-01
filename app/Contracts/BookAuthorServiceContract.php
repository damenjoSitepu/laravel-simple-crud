<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BookAuthorServiceContract {
    /**
     * Find Book Authors
     *
     * @param integer $authorId
     * @return Collection|bool
     */
    public static function findAuthors(int $authorId): Collection | bool;
}