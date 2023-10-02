<?php 
namespace App\Services\Book;

use App\Contracts\BookAuthorServiceContract;
use Illuminate\Database\Eloquent\Collection;

class BookAuthorService implements BookAuthorServiceContract {
    /**
     * Find Book Authors
     *
     * @param integer $authorId
     * @return Collection|bool
     */
    public static function findAuthors(int $authorId): Collection | bool
    {
        try {
            $book = BookService::find($authorId);
            if (empty($book)) return false;
            return $book->orderedAuthors;
        } catch (\Throwable) {
            return false;
        }
    }
}