<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Services\Book\BookService;
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
        return view("app.book.create", [
            "title" => "Book | Create",
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
            BookService::save($request);
            DB::commit();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("success_message",BookService::getMessage()["CREATE_SUCCESS"]);
        } catch (\Throwable $t) {
            DB::rollBack();
            return redirect()->route(BookService::getRoutes()["INDEX"])
                            ->with("success_message",BookService::getMessage()["CREATE_FAIL"]);
        }
    }
}
