@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="flex w-full justify-between">
        <div class="w-5/6">
            @include("components.title",["title" => "Book | Author"])
        </div>
        <div class="w-1/6">
            <a href="{{ route("book.author.assign",[
                "id" => $id,
            ]) }}" class="btn btn-primary capitalize w-full">Assign Author</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @include("components.flash-message")

    {{-- Contents --}}
    <div>
        <div class="overflow-x-auto">
            @if ($bookAuthors->count() === 0) 
                <p class="block text-center">No Book Authors Data!</p>
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
                        @foreach ($bookAuthors as $author)
                            <tr class="hover">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $author->name }}</td>
                                <td>{{ $author->pivot->role }}</td>
                                <td>
                                    {{-- Delete --}}
                                    <form class="inline-block" method="post" action="{{ route('book.author.delete',[
                                        'id' => $id,
                                        'authorId' => $author->id,
                                    ]) }}">
                                        @csrf  
                                        @method('DELETE')
                                        <button class="badge badge-error text-white">Delete</button>
                                    </form>
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