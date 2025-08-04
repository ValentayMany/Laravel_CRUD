@extends('layout')

@section('content')
<h1>All Post</h1>
<a href="{{ route('create') }}" class="btn btn-primary mb-3">Creact New Post</a>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($posts->count())
    @foreach ($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <h3>{{$post->title}}</h3>
                <p>{{Srt::limit($post->content, 100)}}</p>
                <a href="{{ route('show', $post) }}" class="btn btn-secondary">View</a>
                <a href="{{ route('edit', $post) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('destroy', $post) }}" method="POST" style="display: inline" onsubmit="return confirm('Are you sure to delete?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
@else
<div class="alert alert-info">No Post Found</div>
@endif

@endsection
