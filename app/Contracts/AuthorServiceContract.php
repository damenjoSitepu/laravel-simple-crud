<?php
namespace App\Contracts;

use App\Http\Requests\AuthorBookRequest;
use App\Http\Requests\AuthorRequest;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceContract {
    /**
     * Save Author Data
     *
     * @param AuthorRequest $request
     * @return boolean
     */
    public static function save(AuthorRequest $request): bool;

    /**
     * Get Author Routes
     *
     * @return array
     */
    public static function getRoutes(): array;

    /**
     * Get Author Messages
     *
     * @return array
     */
    public static function getMessage(): array;

    /**
     * Get Authors Data
     *
     * @return Collection
     */
    public static function get(): Collection;

    /**
     * Delete Author Data
     *
     * @param integer $id
     * @return boolean
     */
    public static function delete(int $id): bool;

    /**
     * Update Author Data
     *
     * @param integer $id
     * @param AuthorRequest $request
     * @return boolean
     */
    public static function update(int $id, AuthorRequest $request): bool;

    /**
     * Assign A Book To Certain Author
     *
     * @param integer $authorId
     * @param AuthorBookRequest $request
     * @return boolean
     */
    public static function assignBook(int $authorId, AuthorBookRequest $request): bool;
}