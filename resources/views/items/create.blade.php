@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start">
            <h1 class="font-weight-bold">Új termék hozzáadása</h1>
            <div class="btn-toolbar">
                <a href="{{ action('ItemsController@index') }}" class="btn btn-sm btn-link text-decoration-none">Vissza
                    a termékekhez</a>
            </div>
        </div>

        @include('inc.messages')

        {{-- Új termék --}}
        <form action="{{ action('ItemsController@store') }}" method="POST">
            @csrf
            <div class="form-row align-items-end">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Termék megnevezése</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="price">Ár</label>
                        <div class="input-group mb-3">
                            <input type="tel" id="price" name="price" class="form-control"
                                   aria-label="Termék ára" aria-describedby="basic-addon2" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Ft</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="form-group">
                        <button class="btn btn-success">Hozzáadás</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
        });
    </script>
@endsection