<?php 
namespace App\Services\Book;

use App\Contracts\BookServiceContract;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class BookService implements BookServiceContract {
    /**
     * @var array
     */
    private const ROUTES = [
        "INDEX" => "book.index",
        "CREATE" => "book.create",
        "STORE" => "book.store",
    ];

    /**
     * @var array
     */
    private const MESSAGES = [
        "CREATE_SUCCESS" => "Successfully creating book!",
        "CREATE_FAIL" => "Fail when creating book!",
        "DELETE_SUCCESS" => "Successfully deleting book!",
        "DELETE_FAIL" => "There is no book to be deleted!",
        "FIND_FAIL" => "Fail to find book!",
        "UPDATE_SUCCESS" => "Successfully updating book!",
        "UPDATE_FAIL" => "Fail to update book!",
    ];

    /**
     * Get Book Routes
     *
     * @return array
     */
    public static function getRoutes(): array 
    {
        return self::ROUTES;
    }

    /**
     * Get Book Messages
     *
     * @return array
     */
    public static function getMessage(): array 
    {
        return self::MESSAGES;
    }

    /**
     * Get Single Book Data
     *
     * @param integer $id
     * @return Book
     */
    public static function find(int $id): Book
    {
        return Book::find($id);
    }

    /**
     * Get Books Data Unless With Assigned Author Assigned Books
     * @param int $authorId
     * @return Collection
     */
    public static function getUnlessAssignedAuthorBook(int $authorId): Collection 
    {
        try {   
            $author = Author::with(["books"])->find($authorId);
            if (empty($author)) return collect();
            $books = new Book;
            if ($author->books->count() > 0) {
                $books = $books->whereNotIn("id",[$author->books->pluck("id")->toArray()]);
            }
            $books = $books->get();
            return $books;
        } catch (\Throwable $t) {
            return collect();
        }
    }

    /**
     * Get Books Data
     *
     * @return Collection
     */
    public static function get(): Collection
    {
        try {
            return Book::orderBy("id","DESC")->get();
        } catch (\Throwable $t) {
            return collect();
        }
    }

    /**
     * Save Book Data
     *
     * @param BookRequest $request
     * @return boolean
     */
    public static function save(BookRequest $request): bool 
    {
        try {
            return (new Book($request->only(["name","isbn"])))->save();
        } catch (\Throwable $t) {
            return false;
        }
    }

    /**
     * Update Book Data
     *
     * @param integer $id
     * @param BookRequest $request
     * @return boolean
     */
    public static function update(int $id, BookRequest $request): bool
    {
        try {
            $book = Book::find($id);
            if (empty($book)) return false;
            $book->update($request->only(["name","isbn"]));
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Delete Book Data
     *
     * @param integer $id
     * @return boolean
     */
    public static function delete(int $id): bool 
    {
        try {
            $book = Book::where("id",$id)->first();
            if (empty($book)) return false;
            return $book->delete() > 0 ? true : false;
        } catch (\Throwable $t) {
            return false;
        }
    }
}