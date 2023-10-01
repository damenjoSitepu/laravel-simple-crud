<?php
namespace App\Contracts;

use App\Http\Requests\BookAuthorRequest;
use App\Http\Requests\BookRequest;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceContract {
    /**
     * Save Book Data
     *
     * @param BookRequest $request
     * @return boolean
     */
    public static function save(BookRequest $request): bool;

    /**
     * Get Book Routes
     *
     * @return array
     */
    public static function getRoutes(): array;

    /**
     * Get Book Messages
     *
     * @return array
     */
    public static function getMessage(): array;

    /**
     * Get Books Data
     *
     * @return Collection
     */
    public static function get(): Collection;

    /**
     * Delete Book Data
     *
     * @param integer $id
     * @return boolean
     */
    public static function delete(int $id): bool;

    /**
     * Update Book Data
     *
     * @param integer $id
     * @param BookRequest $request
     * @return boolean
     */
    public static function update(int $id, BookRequest $request): bool;

    /**
     * Get Books Data Unless With Assigned Author Assigned Books
     * @param int $authorId
     * @return Collection
     */
    public static function getUnlessAssignedAuthorBook(int $authorId): Collection;

    /**
     * Assign A Author To Certain Book
     *
     * @param integer $bookId
     * @param BookAuthorRequest $request
     * @return array
     */
    public static function assignAuthor(int $bookId, BookAuthorRequest $request): array;

    /**
     * Delete Author For Certain Book
     *
     * @param integer $bookId
     * @param integer $authorId
     * @return array
     */
    public static function deleteAuthor(int $bookId, int $authorId): array;
}