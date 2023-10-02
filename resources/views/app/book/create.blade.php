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
            <input type="text" value="{{ old('name') }}" placeholder="Book Name" name="name" class="input input-bordered w-1/2" />
            @include("components.error-message", ["attribute" => "name"])
        </div>

        <div class="my-3">
            <input type="text" value="{{ old('isbn') }}" placeholder="Book ISBN" name="isbn" class="input input-bordered w-1/2" />
            @include("components.error-message", ["attribute" => "isbn"])
        </div>

        <div class="my-3 w-full">
            @if ($authors->count() === 0)
                <div class="bg-red-100 rounded text-center font-bold w-1/2 p-3 text-red-500">
                    <p>Please create at least one author to create a book!</p>
                </div>
            @else 
            <div class="flex gap-x-4">
                <div class="w-1/2">
                    <select class="select select-bordered w-full" name="author_id">
                        <option disabled selected>Select Author</option>
                        @if ($authors->count() > 0)
                            @foreach ($authors as $author) 
                                <option @if(old("author_id") == $author->id) selected @endif value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach 
                        @endif
                    </select>
                    @include("components.error-message", ["attribute" => "author_id"])
                </div>
            </div>
            @endif 
        </div>

        <button class="btn btn-accent capitalize block my-4">Create</button>
    </form>
</div>
@endSection