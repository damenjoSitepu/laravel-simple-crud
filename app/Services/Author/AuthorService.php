<?php 
namespace App\Services\Author;

use App\Contracts\AuthorServiceContract;
use App\Http\Requests\AuthorBookRequest;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Models\Book;
use App\Services\Book\BookService;
use App\Services\Util\RoleService;
use Illuminate\Database\Eloquent\Collection;

class AuthorService implements AuthorServiceContract {
    /**
     * @var array
     */
    private const ROUTES = [
        "INDEX" => "author.index",
        "CREATE" => "author.create",
        "STORE" => "author.store",
        "BOOK" => "author.book.index",
        "BOOK_ASSIGN" => "author.book.assign"
    ];

    /**
     * @var array
     */
    private const MESSAGES = [
        "CREATE_SUCCESS" => "Successfully creating author!",
        "CREATE_FAIL" => "Fail when creating author!",
        "DELETE_SUCCESS" => "Successfully deleting author!",
        "DELETE_FAIL" => "There is no author to be deleted!",
        "FIND_FAIL" => "Fail to find author!",
        "UPDATE_SUCCESS" => "Successfully updating author!",
        "UPDATE_FAIL" => "Fail to update author!",
        "ASSIGN_SUCCESS" => "Successfully assign books to this author!",
        "ASSIGN_FAIL" => "Fail to assign books to this author!",
        "OWNER_IS_EXISTS" => "This book is already owned by someone!",
    ];

    /**
     * Get Author Routes
     *
     * @return array
     */
    public static function getRoutes(): array 
    {
        return self::ROUTES;
    }

    /**
     * Get Author Messages
     *
     * @return array
     */
    public static function getMessage(): array 
    {
        return self::MESSAGES;
    }

    /**
     * Get Single Author Data
     *
     * @param integer $id
     * @return Author
     */
    public static function find(int $id): Author
    {
        return Author::find($id);
    }

    /**
     * Get Authors Data
     *
     * @return Collection
     */
    public static function get(): Collection
    {
        try {
            return Author::orderBy("id","DESC")->get();
        } catch (\Throwable $t) {
            return collect();
        }
    }

    /**
     * Save Author Data
     *
     * @param AuthorRequest $request
     * @return boolean
     */
    public static function save(AuthorRequest $request): bool 
    {
        try {
            return (new Author($request->only(["name"])))->save();
        } catch (\Throwable $t) {
            return false;
        }
    }

    /**
     * Update Author Data
     *
     * @param integer $id
     * @param AuthorRequest $request
     * @return boolean
     */
    public static function update(int $id, AuthorRequest $request): bool
    {
        try {
            $author = Author::find($id);
            if (empty($author)) return false;
            $author->update($request->only(["name"]));
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Delete Author Data
     *
     * @param integer $id
     * @return boolean
     */
    public static function delete(int $id): bool 
    {
        try {
            $author = Author::where("id",$id)->first();
            if (empty($author)) return false;
            return $author->delete() > 0 ? true : false;
        } catch (\Throwable $t) {
            return false;
        }
    }

    /**
     * Assign A Book To Certain Author
     *
     * @param integer $authorId
     * @param AuthorBookRequest $request
     * @return array
     */
    public static function assignBook(int $authorId, AuthorBookRequest $request): array
    {
        try {
            $author = Author::find($authorId);
            if (empty($author)) return [false, self::getMessage()["ASSIGN_FAIL"]];
            $bookId = $request->book_id;
            if (empty($book = BookService::find($bookId))) return [false, self::getMessage()["ASSIGN_FAIL"]];
            if (! RoleService::checkOwnerIsExists($book, "", false)) return [false, self::getMessage()["ASSIGN_FAIL"]];
            $role = RoleService::getMember();
            $author->books()->attach($bookId, compact(["role"]));
            return [true, self::getMessage()["ASSIGN_SUCCESS"]];
        } catch (\Throwable $t) {
            return [false, self::getMessage()["ASSIGN_FAIL"]];
        }
    }

    /**
     * Get Authors Data Unless Assigned Book Author
     * @param int $bookId
     * @return Collection
     */
    public static function getUnlessAssignedBookAuthor(int $bookId): Collection 
    {
        try {   
            $book = Book::with(["authors"])->find($bookId);
            if (empty($book)) return collect();
            $authors = new Author;
            if ($book->authors->count() > 0) {
                $authors = $authors->whereNotIn("id",$book->authors->pluck("id")->toArray());
            }
            $authors = $authors->get();
            return $authors;
        } catch (\Throwable $t) {
            return collect();
        }
    }
}
