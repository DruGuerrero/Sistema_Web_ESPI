@extends('adminlte::page')

@section('title', 'Servicios y Bienes Académicos')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="d-flex justify-content-between px-2.5">
        <h1>Servicios y Bienes Académicos</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addProductModal">
            Agregar servicio o bien académico
        </button>
    </div>
    <hr>
@stop

@section('content')
    <!-- Formulario de búsqueda -->
    <form action="{{ route('admin.payments.show_products') }}" method="GET" class="form-inline mb-3 px-2.5">
        <div class="input-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Escriba el nombre" value="{{ request()->input('search') }}">
            <span class="input-group-btn px-2">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </span>
        </div>
    </form>

    @if ($products->isEmpty())
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="mt-2 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                <span id="hs-soft-color-info-label" class="font-bold">Info!</span> No existen servicios o bienes académicos registrados.
            </div>
        </div>
    @else
        @php
            Log::info('Loading show_products view');
            $headers = ['Nro', 'Servicio/bien', 'Descripción', 'Precio', 'Acciones'];
            $rows = $products->map(function ($product, $index) use ($products) {
                return [
                    $products->firstItem() + $index,
                    $product->nombre,
                    $product->descripcion,
                    $product->precio . ' Bs',
                    view('components.button-preline', [
                        'attributes' => new \Illuminate\View\ComponentAttributeBag(['data-toggle' => 'modal', 'data-target' => '#editProductModal-' . $product->id]),
                        'slot' => 'Editar'
                    ])->render() . ' ' .
                    view('components.button-preline', [
                        'attributes' => new \Illuminate\View\ComponentAttributeBag(['data-toggle' => 'modal', 'data-target' => '#deleteProductModal-' . $product->id]),
                        'slot' => 'Eliminar'
                    ])->render()
                ];
            })->toArray();
        @endphp

        <x-table :headers="$headers" :rows="$rows" />

        <!-- Agregar paginación -->
        <x-pagination :paginator="$products" />
    @endif
    <div class="px-2.5">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary mb-3">Volver</a>
    </div>

    <!-- Modal para agregar producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Agregar servicio o bien académico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="POST" action="{{ route('admin.products.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre del servicio o bien académico</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <x-price-input
                                id="hs-input-price"
                                name="precio"
                                placeholder="0.00"
                                currencySymbol="Bs"
                                currency="BOB"
                            />
                        </div>
                        <div class="modal-footer flex justify-between mt-6">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar producto -->
    @foreach($products as $product)
        <div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel-{{ $product->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel-{{ $product->id }}">Editar Servicio o Bien Académico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm-{{ $product->id }}" method="POST" action="{{ route('admin.products.update', $product->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nombre">Nombre del servicio o bien académico</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $product->nombre }}" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" required>{{ $product->descripcion }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio</label>
                                <x-price-input
                                    id="hs-input-price-{{ $product->id }}"
                                    name="precio"
                                    placeholder="0.00"
                                    currencySymbol="Bs"
                                    currency="BOB"
                                    value="{{ $product->precio }}"
                                />
                            </div>
                            <div class="modal-footer flex justify-between mt-6">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Modal para eliminar producto -->
    @foreach($products as $product)
        <div class="modal fade" id="deleteProductModal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel-{{ $product->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProductModalLabel-{{ $product->id }}">Eliminar servicio o bien académico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>¿Seguro/a que quiere eliminar este item?</strong></p>
                        <p>Esta acción no podrá deshacerse.</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteProductForm-{{ $product->id }}" method="POST" action="{{ route('admin.products.destroy', $product->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop