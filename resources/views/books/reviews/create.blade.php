<x-layout>
    <h1 class="mb-10 text-2xl">Add a review for {{$book->title}}</h1>

    <form action="{{route('books.review.store', $book)}}" method="POST">
        @csrf
        @foreach ($errors->all() as $error)
        <p>{{$error}}</p>
        @endforeach
        <label for="content">Content</label>
        <textarea name="content" id="review" class="input mb-4"></textarea>
        
        <label for="stars">Ratings</label>
        <select name="stars" id="ratings" class="input mb-4">
            @for ($i = 1; $i <= 5; $i++)

                <option value="{{$i}}">
                    {{$i}}
                </option>
            @endfor
        </select>

        <button type="submit" class="btn">Submit</button>
    </form>
</x-layout>