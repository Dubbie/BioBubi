@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelők</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-dark">Új megrendelő</a>
            </div>
        </div>

        {{-- Megrendelők --}}
        @if(count($customers) > 0)
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Név</th>
                        <th scope="col">Város</th>
                        <th scope="col">Telefonszám</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Típus</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr class="tr-clickable" data-redirect-to="{{ action('CustomersController@show', $customer) }}">
                        <td class="text-muted">{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->address->city }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->getResellerLabel() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="lead mb-2">Ajjaj! Nincsen még megrendelő az adatbázisban.</p>
            <p><a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-secondary">Megrendelő hozzáadása</a></p>
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