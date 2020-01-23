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

        {{-- Esedékes teendők --}}
        <div class="card card-body border-0 shadow-sm mb-4">
            <h5 class="font-weight-bold mb-4">Esedékes teendők</h5>
            @if(count($alerts) > 0)
                @foreach($alerts as $alert)
                    <div class="action-hover-only d-flex justify-content-between @if(last($alerts) != $alert) mb-2 @endif">
                        <div class="alert-details">
                            <p class="mb-0" style="line-height: 1;">
                                <span style="color: {{ $alert->user->color }}">{{ $alert->user->name }}</span>
                                <span class="text-muted mx-2">•</span>
                                <span class="font-weight-bold" data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ $alert->time->format('Y M d, H:i') }}">{{ $alert->getRemainingLabel() }}</span>
                            </p>
                            <p class="mb-0" style="line-height: 1;">
                                <span class="msg">{{ $alert->message }}</span>
                                <a href="{{ action('CustomersController@show', $alert->customer) }}"
                                   class="btn btn-sm btn-link text-decoration-none">{{ $alert->customer->name }}</a>
                            </p>
                        </div>
                        <div class="td-action alert-actions">
                            {{-- Teljesítés --}}
                            <form action="{{ action('AlertsController@complete', $alert) }}" class="d-inline-block"
                                  method="POST" data-toggle="tooltip" data-placement="top" title="Teendő teljesítése">
                                @csrf
                                <button class="btn btn-complete-alert btn-sm btn-muted px-1 py-0">
                                    <span class="icon">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </button>
                            </form>
                            {{-- Módosítás --}}
                            <span data-toggle="tooltip" data-placement="top" title="Teendő szerkesztése">
                                <button type="button" class="btn btn-edit-alert btn-sm btn-muted px-1 py-0"
                                        data-toggle="modal"
                                        data-target="#editAlertModal"
                                        data-alert-id="{{ $alert->id }}"
                                        data-message="{{ $alert->message }}"
                                        data-time="{{ $alert->time->format('Y/m/d H:i') }}">
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                            </button>
                            </span>
                            {{-- Törlés --}}
                            <form action="{{ action('AlertsController@delete', $alert) }}" class="d-inline-block"
                                  method="POST" data-toggle="tooltip" data-placement="top" title="Teendő törlése">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-del-alert btn-sm btn-muted px-1 py-0">
                                    <span class="icon">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </button>
                            </form>
                        </div> {{-- Módósítás, Törlés, Teljesítés vége --}}
                    </div>
                @endforeach
            @else
                <div class="d-flex align-items-center">
                    <img src="https://img.icons8.com/color/48/000000/checked--v1.png" class="d-inline-block mr-2"
                         style="width: 48px; height: 48px;">
                    <p class="lead mb-0">Nincsenek esedékes teendők.</p>
                </div>
            @endif
        </div>

        {{-- Megrendelők --}}
        @if(count($customers) > 0)
            <div class="row">
                <div class="col-lg-3">
                    @include('inc.sidebar')
                </div>
                <div class="col-lg-9">
                    <table id="customers-table" class="table table-sm table-hover table-borderless">
                        <thead class="">
                        <tr>
                            <th scope="col" class="tr-clickable" data-sort="string">Név</th>
                            <th scope="col" class="tr-clickable" data-sort="string">Város</th>
                            <th scope="col" class="tr-clickable" data-sort="int">Telefonszám</th>
                            <th scope="col" class="tr-clickable" data-sort="string">E-mail</th>
                            <th scope="col" class="tr-clickable" data-sort="string">Típus</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr class="tr-clickable action-hover-only"
                                data-redirect-to="{{ action('CustomersController@show', $customer) }}">
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->address->city }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->getResellerLabel() }}</td>
                                <td class="td-action text-right">
                                    {{-- Szerkesztés --}}
                                    <a href="{{ action('CustomersController@edit', $customer) }}"
                                       class="btn btn-sm btn-muted p-1" data-toggle="tooltip" data-placement="top" title="Megrendelő szerkesztése">
                                        <span class="icon">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    </a>

                                    {{-- Törlés --}}
                                    <form action="{{ action('CustomersController@delete', $customer) }}"
                                          class="d-inline-block" method="POST" data-toggle="tooltip" data-placement="top" title="Megrendelő törlése">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-del-customer btn-sm btn-muted px-1 py-0">
                                            <span class="icon">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $customers->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <p class="lead mb-2">Nincsen még megrendelő az adatbázisban.</p>
            <p><a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-primary">Megrendelő
                    hozzáadása</a></p>
        @endif
    </div>

    {{-- Modalok --}}
    @include('inc.modals.edit_alert')
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            const formFilter = $('#form-filter');

            // Megrendelő törlő gomb
            $('.btn-del-customer').on('click', (e) => {
                if (!confirm('Biztosan törölni szeretnéd a terméket? Ez a folyamat nem visszafordítható.')) {
                    e.preventDefault();
                }
            });

            // Teendő módosítása gomb
            $('.btn-edit-alert').on('click', (e) => {
                const tgt = e.currentTarget;
                document.getElementById('edit_alert_id').value = tgt.dataset.alertId;
                document.getElementById('edit_alert_message').value = tgt.dataset.message;
                document.getElementById('edit_alert_time').value = tgt.dataset.time;
            });

            // Teendő törlési biztonsági
            $('.btn-del-alert').on('click', (e) => {
                if (!confirm('Biztosan törölni szeretnéd a teendőt? Ez a folyamat nem visszafordítható!')) {
                    e.preventDefault();
                }
            });

            // Kattintható sorok
            $('.tr-clickable').on('click', (e) => {
                if (e.currentTarget.dataset.redirectTo &&
                    $(e.target).closest('.btn-del-customer').length === 0
                ) {
                    window.location = e.currentTarget.dataset.redirectTo
                }
            });

            // Reset
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

            // Stupid table
            $("#customers-table").stupidtable();

            // Tooltipek
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });

            // Datetime picker teendőhöz
            $.datetimepicker.setLocale('hu');
            $('#edit_alert_time').datetimepicker({
                dayOfWeekStart: 1,
            });
        });
    </script>
@endsection