@extends('adminlte::page')

@section('title', $category['name'])

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>{{ $category['name'] }}</h1>
@stop

@section('content')
    <p>{{ strip_tags($category['description']) }}</p>
    <div class="row">
        @foreach($subCategories as $subCategory)
            <div class="col-md-6 mb-4">
                <x-advanced-card
                    title="{{ $subCategory['name'] }}"
                    content="{{ strip_tags($subCategory['description']) }}"
                    :contentBlocks="$coursesAndProfessors[$subCategory['id']] ?? []"
                    leftButtonLink="#"
                    rightButtonLink="#"
                />
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.index') }}" class="btn btn-secondary">Volver</a>
@stop

@section('js')
    <script>
        console.log(@json($coursesAndProfessors));
    </script>
@stop