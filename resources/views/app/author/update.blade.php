@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="w-full">
        <div class="flex flex-wrap justify-normal">
            @include("components.title",["title" => "Author | Update"])
            <a href="{{ route("author.index") }}" class="btn btn-error capitalize w-36">Back</a>
        </div>
    </div>

    {{-- Input --}}
    <form class="my-5" method="POST" action="{{ route("author.edit",[
        "id" => $author->id,
    ]) }}">
        @csrf
        @method("PATCH")
        <input type="text" placeholder="Author Name" name="name" value="{{ $author->name }}" class="input input-bordered w-1/2" />
        @include("components.error-message", ["attribute" => "name"])
        <button class="btn btn-accent capitalize block my-4">Update</button>
    </form>
</div>
@endSection