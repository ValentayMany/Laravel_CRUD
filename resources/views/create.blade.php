@extends('layout')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="content">Content</label>
            <textarea class="form-control" name="content" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Creact</button>
        <a href="{{ route('index') }}" class= "btn btn-Scondary">Back</a>
    </form>

@endsection
