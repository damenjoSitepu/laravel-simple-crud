<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorBookRequest;
use App\Http\Requests\AuthorRequest;
use App\Services\Author\AuthorBookService;
use App\Services\Author\AuthorService;
use App\Services\Book\BookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthorController extends Controller
{
    /**
     * Define A View For Author 
     *
     * @return View
     */
    public function index(): View
    {
        $authors = AuthorService::get();
        return view("app.author.index",[
            "title" => "Author",
            ...compact(["authors"]),
        ]);
    }

    /**
     * Define A View For Author Books
     *
     * @param integer $id
     * @return View|RedirectResponse
     */
    public function book(int $id): View|RedirectResponse
    {
        try {
            $authorBooks = AuthorBookService::findBooks($id);
            if (! $authorBooks) {
                return redirect()->route(AuthorService::getRoutes()["INDEX"])
                                ->with("error_message",AuthorService::getMessage()["FIND_FAIL"]);
            }
        } catch (\Throwable $t) {
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("error_message",AuthorService::getMessage()["FIND_FAIL"]);
        }

        return view("app.author.book.index", [
            "title" => "Author | Book",
            ...compact(["id","authorBooks"]),
        ]);
    }

    /**
     * Define A View For Author Create
     *
     * @return View
     */
    public function create(): View 
    {
        return view("app.author.create", [
            "title" => "Author | Create",
        ]);
    }

    /**
     * Define A View For Author Assign Book
     *
     * @param int $id
     * @return View
     */
    public function assignBook(int $id): View 
    {
        $books = collect();
        try {
            $books = BookService::getUnlessAssignedAuthorBook($id);
        } catch (\Throwable $t) {}

        return view("app.author.book.assign", [
            "title" => "Author | Assign Book",
            ...compact(["id","books"]),
        ]);
    }

    /**
     * Define A View For Author Update
     *
     * @return View|RedirectResponse
     */
    public function update(int $id): View|RedirectResponse
    {
        try {
            $author = AuthorService::find($id);
        } catch (\Throwable $t) {
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("error_message",AuthorService::getMessage()["FIND_FAIL"]);
        }

        return view("app.author.update", [
            "title" => "Author | Update",
            ...compact(["author"]),
        ]);
    }

    /**
     * Edit The Author Data (SINGLE)
     *
     * @param integer $id
     * @param AuthorRequest $request
     * @return RedirectResponse
     */
    public function edit(int $id, AuthorRequest $request): RedirectResponse
    { 
        try {
            DB::beginTransaction();
            $updatedAuthor = AuthorService::update($id, $request);
            if (! $updatedAuthor) {
                return redirect()->route(AuthorService::getRoutes()["INDEX"])
                                ->with("error_message",AuthorService::getMessage()["UPDATE_FAIL"]);
            } 
            DB::commit();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("success_message",AuthorService::getMessage()["UPDATE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("error_message",AuthorService::getMessage()["UPDATE_FAIL"]);
        }
    }

    /**
     * Destroy The Author Data (SINGLE)
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse 
    {
        try {
            DB::beginTransaction();
            $deletedAuthor = AuthorService::delete($id);
            if (! $deletedAuthor) {
                return redirect()->route(AuthorService::getRoutes()["INDEX"])
                                ->with("error_message",AuthorService::getMessage()["DELETE_FAIL"]);
            }
            DB::commit();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("success_message",AuthorService::getMessage()["DELETE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("error_message",AuthorService::getMessage()["DELETE_FAIL"]);
        }
    }

    /**
     * Store The Author Books When They've assigned
     *
     * @param integer $id
     * @param AuthorBookRequest $request
     * @return void
     */
    public function storeBook(int $id, AuthorBookRequest $request)
    {
        try {
            DB::beginTransaction();
            if (! AuthorService::assignBook($id,$request)) {
                return redirect()->route(AuthorService::getRoutes()["BOOK"], compact(["id"]))
                                ->with("error_message",AuthorService::getMessage()["ASSIGN_FAIL"]);
            }
            DB::commit();
            return redirect()->route(AuthorService::getRoutes()["BOOK"], compact(["id"]))
                            ->with("success_message",AuthorService::getMessage()["ASSIGN_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollback();
            return redirect()->route(AuthorService::getRoutes()["BOOK"], compact(["id"]))
                            ->with("error_message",AuthorService::getMessage()["ASSIGN_FAIL"]);
        }
    }

    /**
     * Store The Author Data After Being Created
     *
     * @return RedirectResponse
     */
    public function store(AuthorRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            AuthorService::save($request);
            DB::commit();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("success_message",AuthorService::getMessage()["CREATE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(AuthorService::getRoutes()["INDEX"])
                            ->with("success_message",AuthorService::getMessage()["CREATE_FAIL"]);
        }
    }
}
