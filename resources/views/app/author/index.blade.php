@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="flex w-full justify-between">
        <div class="w-5/6">
            @include("components.title",["title" => "Author"])
        </div>
        <div class="w-1/6">
            <a href="{{ route("author.create") }}" class="btn btn-primary capitalize w-full">Create</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @include("components.flash-message")

    {{-- Contents --}}
    <div>
        <div class="overflow-x-auto">
            @if ($authors->count() === 0) 
                <p class="block text-center">No Authors Data!</p>
            @else 
                <table class="table">
                    <thead>
                        <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authors as $author)
                            <tr class="hover">
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $author->name }}</td>
                                <td>
                                    {{-- Delete --}}
                                    <form class="inline-block" method="post" action="{{ route('author.delete',[
                                        'id' => $author->id,
                                    ]) }}">
                                        @csrf  
                                        @method('DELETE')
                                        <button class="badge badge-error text-white">Delete</button>
                                    </form>
                                    {{-- Update --}}
                                    <a href="{{ route("author.update", [
                                        "id" => $author->id,
                                    ]) }}" class="badge badge-warning capitalize w-fit">Update</a>
                                    {{-- View Author Books --}}
                                    <a href="{{ route("author.book.index", [
                                        "id" => $author->id
                                    ]) }}" class="badge badge-accent capitalize w-fit">View Books</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endSection