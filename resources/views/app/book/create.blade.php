@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="w-full">
        <div class="flex flex-wrap justify-normal">
            @include("components.title",["title" => "Book | Create"])
            <a href="{{ route("book.index") }}" class="btn btn-error capitalize w-36">Back</a>
        </div>
    </div>

    {{-- Input --}}
    <form class="my-5" method="POST" action="{{ route("book.store") }}">
        @csrf
        <div class="my-3">
            <input type="text" placeholder="Book Name" name="name" class="input input-bordered w-1/2" />
            @include("components.error-message", ["attribute" => "name"])
        </div>

        <div class="my-3">
            <input type="text" placeholder="Book ISBN" name="isbn" class="input input-bordered w-1/2" />
            @include("components.error-message", ["attribute" => "isbn"])
        </div>

        <button class="btn btn-accent capitalize block my-4">Create</button>
    </form>
</div>
@endSection