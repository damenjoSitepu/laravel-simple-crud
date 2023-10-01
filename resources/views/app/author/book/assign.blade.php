@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="w-full">
        <div class="flex flex-wrap justify-normal">
            @include("components.title",["title" => "Author | Assign Book"])
            <a href="{{ route("author.book.index", compact(["id"])) }}" class="btn btn-error capitalize w-36">Back</a>
        </div>
    </div>

    {{-- Input --}}
    <form class="my-5" method="POST" action="{{ route("author.book.store",compact(["id"])) }}">
        @csrf
        <select class="select select-bordered w-full max-w-xs" name="book_id">
            <option disabled selected>Select Book</option>
            @if ($books->count() > 0)
                @foreach ($books as $book) 
                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                @endforeach 
            @endif
        </select>
        @include("components.error-message", ["attribute" => "book_id"])
        <button class="btn btn-accent capitalize block my-4">Assign</button>
    </form>
</div>
@endSection