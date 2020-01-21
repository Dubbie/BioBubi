@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Termékek</h1>
            <div class="btn-toolbar">
                <a href="{{ action('ItemsController@create') }}" class="btn btn-sm btn-outline-dark">Új termék</a>
            </div>
        </div>

        {{-- Megrendelők --}}
        @if(count($items) > 0)
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Név</th>
                        <th scope="col">Ár</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr class="tr-clickable" data-redirect-to="{{ action('ItemsController@show', $item) }}">
                        <td class="text-muted">{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->price }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="lead mb-2">Ajjaj! Nincsen még termék az adatbázisban.</p>
            <p><a href="{{ action('ItemsController@create') }}" class="btn btn-sm btn-primary">Termék hozzáadása</a></p>
        @endif
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
           $('.tr-clickable').on('click', (e) => {
               console.log(e);
               window.location = e.currentTarget.dataset.redirectTo
           });
        });
    </script>
@endsection