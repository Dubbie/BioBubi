@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelők</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-dark">Új
                    megrendelő</a>
            </div>
        </div>

        {{-- Megrendelők --}}
        @if(count($customers) > 0)
            <div class="row">
                <div class="col-lg-3">
                    <div class="card card-body border-0 shadow">
                        <form id="form-filter" action="{{ action('CustomersController@index') }}" method="GET">
                            <p class="mb-0">
                                <small class="font-weight-bold">Szűrők</small>
                            </p>

                            <div class="form-group">
                                <label for="filter-name">Név tartalmzza</label>
                                <input type="text" id="filter-name" name="filter-name"
                                       class="form-control form-control-sm" value="{{ $filter['name'] ?? '' }}">
                            </div>

                            <p class="mb-0">Város</p>
                            <div class="filter overflow-auto mb-4">
                                @foreach($cities as $city)
                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filter-city"
                                                   name="filter-city[]"
                                                   id="filter-city-{{ \Illuminate\Support\Str::slug($city['city']) }}"
                                                   value="{{ $city['city'] }}"
                                                   @if(isset($city['checked'])) checked @endif>
                                            <label class="custom-control-label"
                                                   for="filter-city-{{ \Illuminate\Support\Str::slug($city['city']) }}">{{ $city['city'] }}
                                                <span class="text-muted">{{ $city['total'] }}</span></label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group mb-0 d-flex justify-content-between">
                                <button type="button" id="btn-reset-filter-form"
                                        class="btn btn-sm btn-link text-secondary px-0">Visszaállít
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary">Szűrés</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-9">
                    <table class="table table-sm table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col">Város</th>
                            <th scope="col">Telefonszám</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Típus</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr class="tr-clickable"
                                data-redirect-to="{{ action('CustomersController@show', $customer) }}">
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->address->city }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->getResellerLabel() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $customers->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <p class="lead mb-2">Ajjaj! Nincsen még megrendelő az adatbázisban.</p>
            <p><a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-secondary">Megrendelő
                    hozzáadása</a></p>
        @endif
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            const formFilter = $('#form-filter');
            $('.tr-clickable').on('click', (e) => {
                console.log(e);
                window.location = e.currentTarget.dataset.redirectTo
            });

            $('#btn-reset-filter-form').on('click', () => {
                // Ürítsük a nevet
                $('#filter-name').val('');

                $('.filter-city').each((i, el) => {
                    el.checked = false;
                });

                formFilter.submit();
            });

            formFilter.submit(function () {
                $(this).find(":input").filter(function () {
                    return !this.value;
                }).attr("disabled", "disabled");
            });
            // Un-disable form fields when page loads, in case they click back after submission or client side validation
            formFilter.find(":input").prop("disabled", false);
        });
    </script>
@endsection