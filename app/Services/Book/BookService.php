<?php 
namespace App\Services\Book;

use App\Contracts\BookServiceContract;
use App\Http\Requests\BookAuthorRequest;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Services\Author\AuthorService;
use App\Services\Util\RoleService;
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
        "AUTHOR" => "book.author.index",
        "AUTHOR_ASSIGN" => "book.author.assign"
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
        "ASSIGN_SUCCESS" => "Successfully assign authors to this book!",
        "ASSIGN_FAIL" => "Fail to assign authors to this book!",
        "OWNER_ALREADY_EXISTS" => "Owner role has already exists for this book!",
        "DELETE_AUTHOR_SUCCESS" => "Sucessfully removing author from this book!",
        "DELETE_AUTHOR_FAIL" => "Fail to removing author from this book!",
        "CANNOT_REMOVE_OWNER" => "You cannot remove owner who've creating this book!",
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
                $books = $books->whereNotIn("id",$author->books->pluck("id")->toArray());
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
            if (empty(AuthorService::find($request->author_id))) return false;
            $book = Book::create($request->only(["name","isbn"]));
            $role = RoleService::getOwner();
            $book = $book->authors()->attach($request->author_id,compact(["role"]));
            return true;
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

    /**
     * Assign A Author To Certain Book
     *
     * @param integer $bookId
     * @param BookAuthorRequest $request
     * @return array
     */
    public static function assignAuthor(int $bookId, BookAuthorRequest $request): array
    {
        try {
            if (! RoleService::checkRole($request->role)) return [false, self::getMessage()["ASSIGN_FAIL"]];
            $book = Book::with(["authors"])->find($bookId);
            if (empty($book)) return [false, self::getMessage()["ASSIGN_FAIL"]];
            if (RoleService::checkOwnerIsExists($book, (string) $request->role)) return [false, self::getMessage()["OWNER_ALREADY_EXISTS"]];
            $authorId = $request->author_id;
            $role = $request->role;
            if (empty(AuthorService::find($authorId))) return [false, self::getMessage()["ASSIGN_FAIL"]];
            $book->authors()->attach($authorId, compact(["role"]));
            return [true, self::getMessage()["ASSIGN_SUCCESS"]];
        } catch (\Throwable $t) {
            return [false, self::getMessage()["ASSIGN_FAIL"]];
        }
    }

    /**
     * Delete Author For Certain Book
     *
     * @param integer $bookId
     * @param integer $authorId
     * @return array
     */
    public static function deleteAuthor(int $bookId, int $authorId): array 
    {
        try {
            $book = Book::with(["authors"])->find($bookId);
            if (empty($book)) return [false, self::getMessage()["DELETE_AUTHOR_FAIL"]];
            if ($book->authors->count() > 0) {
                $author = $book->authors->where("id",$authorId)->first();
                if (! empty($author)) {
                    try {
                        if ($author->pivot->role === RoleService::getOwner()) return [false, self::getMessage()["CANNOT_REMOVE_OWNER"]];
                    } catch (\Throwable $t) {}
                }
            }
            $book = $book->authors()->detach($authorId);
            return [true, self::getMessage()["DELETE_AUTHOR_SUCCESS"]];
        } catch (\Throwable $t) {
            return [false, self::getMessage()["DELETE_AUTHOR_FAIL"]];
        }
    }
}