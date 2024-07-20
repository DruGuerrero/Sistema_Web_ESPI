@extends('adminlte::page')

@section('title', $category['name'])

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>{{ $category['name'] }}</h1>
    <hr>
@stop

@section('content')
    <p class="py-0.5">{{ strip_tags($category['description']) }}</p>
    <div class="row">
        @foreach($subCategories as $subCategory)
            <div class="col-md-6 mb-4">
                <x-advanced-card
                    title="{{ $subCategory['name'] }}"
                    content="{{ strip_tags($subCategory['description']) }}"
                    :contentBlocks="$coursesAndProfessors[$subCategory['id']] ?? []"
                    leftButtonLink="#"
                    rightButtonLink="{{ route('admin.academic.show_subcategory', ['id' => $subCategory['id']]) }}"
                />
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.index') }}" class="btn btn-primary">Volver</a>
@stop

@section('js')
    <script>
        console.log(@json($coursesAndProfessors));
    </script>
@stop