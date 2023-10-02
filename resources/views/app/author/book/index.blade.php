@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="flex w-full justify-between">
        <div class="w-5/6">
            @include("components.title",["title" => "Author (" . $author->name . ") | Book"])
        </div>
        <div class="w-1/6">
            <a href="{{ route("author.book.assign",[
                "id" => $id,
            ]) }}" class="btn btn-primary capitalize w-full">Assign Book</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @include("components.flash-message")

    {{-- Contents --}}
    <div>
        <div class="overflow-x-auto">
            @if ($authorBooks->count() === 0) 
                <p class="block text-center">No Author Books Data!</p>
            @else 
                <table class="table">
                    <thead>
                        <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authorBooks as $book)
                            <tr class="hover">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->pivot->role }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endSection