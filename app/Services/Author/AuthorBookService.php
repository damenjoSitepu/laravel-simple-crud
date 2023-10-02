<?php 
namespace App\Services\Author;

use App\Contracts\AuthorBookServiceContract;
use Illuminate\Database\Eloquent\Collection;

class AuthorBookService implements AuthorBookServiceContract {
    /**
     * Find Author Books
     *
     * @param integer $authorId
     * @return Collection|bool
     */
    public static function findBooks(int $authorId): Collection | bool
    {
        try {
            $author = AuthorService::find($authorId);
            if (empty($author)) return false;
            return $author->orderedBooks;
        } catch (\Throwable) {
            return false;
        }
    }
}