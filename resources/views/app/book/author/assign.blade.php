@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="w-full">
        <div class="flex flex-wrap justify-normal">
            @include("components.title",["title" => "Book | Assign Author"])
            <a href="{{ route("book.author.index", compact(["id"])) }}" class="btn btn-error capitalize w-36">Back</a>
        </div>
    </div>

    {{-- Input --}}
    <form class="my-5" method="POST" action="{{ route("book.author.store",compact(["id"])) }}">
        @csrf
        <div class="flex gap-x-4">
            <div class="w-1/2">
                <select class="select select-bordered w-full" name="author_id">
                    <option disabled selected>Select Author</option>
                    @if ($authors->count() > 0)
                        @foreach ($authors as $author) 
                            <option @if (old('author_id') == $author->id) selected @endif value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach 
                    @endif
                </select>
                @include("components.error-message", ["attribute" => "author_id"])
            </div>

            <div class="w-1/2">
                <select class="select select-bordered w-full" name="role">
                    <option disabled selected>Select Role</option>
                    @if (count($roles) > 0)
                        @foreach ($roles as $role) 
                            <option @if (old('role') == $role) selected @endif value="{{ $role }}">{{ $role }}</option>
                        @endforeach 
                    @endif
                </select>
                @include("components.error-message", ["attribute" => "role"])
            </div>
        </div>
        <button class="btn btn-accent capitalize block my-4">Assign</button>
    </form>
</div>
@endSection