<!-- resources/views/memes/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Mèmes</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="row">
            @foreach($memes as $meme)
                <div class="col-md-4 mb-4">
                    <div class="card">
                    <img src="{{ asset('storage/memes/' . basename($meme->image)) }}" class="card-img-top" alt="Meme Image">
                        <div class="card-body">
                            <p class="card-text">{{ $meme->text }}</p>
                            <a href="{{ route('memes.edit', $meme->id) }}" class="btn btn-primary">Editer</a>
                            <form action="{{ route('memes.destroy', $meme->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Meme ?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
