<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookAuthorRequest;
use App\Http\Requests\BookRequest;
use App\Services\Author\AuthorService;
use App\Services\Book\BookAuthorService;
use App\Services\Book\BookService;
use App\Services\Util\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Define A View For Book 
     *
     * @return View
     */
    public function index(): View
    {
        $books = BookService::get();
        return view("app.book.index",[
            "title" => "Book",
            ...compact(["books"]),
        ]);
    }

    /**
     * Define A View For Book Create
     *
     * @return View
     */
    public function create(): View 
    {
        $authors = AuthorService::get();
        $roles = RoleService::getRoles();

        return view("app.book.create", [
            "title" => "Book | Create",
            ...compact(["authors","roles"]),
        ]);
    }

    /**
     * Define A View For Book Update
     *
     * @return View|RedirectResponse
     */
    public function update(int $id): View|RedirectResponse
    {
        try {
            $book = BookService::find($id);
        } catch (\Throwable $t) {
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("error_message",BookService::getMessage()["FIND_FAIL"]);
        }

        return view("app.book.update", [
            "title" => "Book | Update",
            ...compact(["book"]),
        ]);
    }

    /**
     * Edit The Book Data (SINGLE)
     *
     * @param integer $id
     * @param BookRequest $request
     * @return RedirectResponse
     */
    public function edit(int $id, BookRequest $request): RedirectResponse
    { 
        try {
            DB::beginTransaction();
            $updatedBook = BookService::update($id, $request);
            if (! $updatedBook) {
                return redirect()->route(BookService::getRoutes()["INDEX"])
                                ->with("error_message",BookService::getMessage()["UPDATE_FAIL"]);
            } 
            DB::commit();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("success_message",BookService::getMessage()["UPDATE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("error_message",BookService::getMessage()["UPDATE_FAIL"]);
        }
    }

    /**
     * Destroy Specific Author For Certain Book
     *
     * @param integer $id
     * @param integer $authorId
     * @return RedirectResponse
     */
    public function destroyAuthor(int $id, int $authorId): RedirectResponse
    {   
        try {
            DB::beginTransaction();
            [$deletedAuthor, $message] = BookService::deleteAuthor($id, $authorId);
            if (! $deletedAuthor) {
                return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                                ->with("error_message",$message);
            }
            DB::commit();
            return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                             ->with("success_message",$message);
        } catch (\Throwable $t) {
            DB::rollback();
            return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                            ->with("error_message",BookService::getMessage()["DELETE_AUTHOR_FAIL"]);
        }
    }

    /**
     * Destroy The Book Data (SINGLE)
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse 
    {
        try {
            DB::beginTransaction();
            $deletedBook = BookService::delete($id);
            if (! $deletedBook) {
                return redirect()->route(BookService::getRoutes()["INDEX"])
                                ->with("error_message",BookService::getMessage()["DELETE_FAIL"]);
            }
            DB::commit();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("success_message",BookService::getMessage()["DELETE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("error_message",BookService::getMessage()["DELETE_FAIL"]);
        }
    }

    /**
     * Store The Book Data After Being Created
     *
     * @return RedirectResponse
     */
    public function store(BookRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $savedBook = BookService::save($request);
            if (! $savedBook) {
                return redirect()->route(BookService::getRoutes()["INDEX"])
                                ->with("error_message",BookService::getMessage()["CREATE_FAIL"]);
            }
            DB::commit();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("success_message",BookService::getMessage()["CREATE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("error_message",BookService::getMessage()["CREATE_FAIL"]);
        }
    }

    /**
     * Define A View For Book Authors
     *
     * @param integer $id
     * @return View|RedirectResponse
     */
    public function author(int $id): View|RedirectResponse
    {
        try {
            $bookAuthors = BookAuthorService::findAuthors($id);
            if (! $bookAuthors) {
                return redirect()->route(BookService::getRoutes()["INDEX"])
                                ->with("error_message",BookService::getMessage()["FIND_FAIL"]);
            }
        } catch (\Throwable $t) {
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("error_message",BookService::getMessage()["FIND_FAIL"]);
        }

        return view("app.book.author.index", [
            "title" => "Book | Author",
            ...compact(["id","bookAuthors"]),
        ]);
    }

    /**
     * Define A View For Book Assign Author
     *
     * @param int $id
     * @return View
     */
    public function assignAuthor(int $id): View 
    {
        $authors = collect();
        try {
            $authors = AuthorService::getUnlessAssignedBookAuthor($id);
        } catch (\Throwable $t) {}

        $roles = RoleService::getRoles();
        if (! empty($book = BookService::find($id))) {
            if (RoleService::checkOwnerIsExists($book, "", false)) {
                $roles = array_filter($roles, fn($role) => $role !== RoleService::getOwner());
            }
        }

        return view("app.book.author.assign", [
            "title" => "Book | Assign Author",
            ...compact(["id","authors","roles"]),
        ]);
    }

    /**
     * Store The Book Authors When They've assigned
     *
     * @param integer $id
     * @param BookAuthorRequest $request
     * @return void
     */
    public function storeAuthor(int $id, BookAuthorRequest $request)
    {
        try {
            DB::beginTransaction();
            [$assignedAuthor, $message] = BookService::assignAuthor($id,$request);
            if (! $assignedAuthor) {
                return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                                ->with("error_message",$message);
            }
            DB::commit();
            return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                            ->with("success_message",$message);
        } catch (\Throwable $t) {
            DB::rollback();
            return redirect()->route(BookService::getRoutes()["AUTHOR"], compact(["id"]))
                            ->with("error_message",BookService::getMessage()["ASSIGN_FAIL"]);
        }
    }
}
