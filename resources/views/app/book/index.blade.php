@extends("layouts.main-layout")

@section("contents")
<div class="px-4 my-4">
    <div class="flex w-full justify-between">
        <div class="w-5/6">
            @include("components.title",["title" => "Book"])
        </div>
        <div class="w-1/6">
            <a href="{{ route("book.create") }}" class="btn btn-primary capitalize w-full">Create</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @include("components.flash-message")

    {{-- Contents --}}
    <div>
        <div class="overflow-x-auto">
            @if ($books->count() === 0) 
                <p class="block text-center">No Books Data!</p>
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
                        @foreach ($books as $book)
                            <tr class="hover">
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $book->name }}</td>
                                <td>
                                    {{-- Delete --}}
                                    <form class="inline-block" method="post" action="{{ route('book.delete',[
                                        'id' => $book->id,
                                    ]) }}">
                                        @csrf  
                                        @method('DELETE')
                                        <button class="badge badge-error text-white">Delete</button>
                                    </form>
                                    {{-- Update --}}
                                    <a href="{{ route("book.update", [
                                        "id" => $book->id,
                                    ]) }}" class="badge badge-warning capitalize w-fit">Update</a>
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