@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelők</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-teal shadow">
                    <span>Új megrendelő</span>
                </a>
            </div>
        </div>

        {{-- Esedékes teendők --}}
        @include('inc.todos')

        {{-- Megrendelők --}}
        @if(count($customers) > 0)
            <div class="row">
                <div class="col-lg-3">
                    @include('inc.sidebar')
                </div>
                <div class="col-lg-9">
                    {{-- Új --}}
                    <div class="card card-body shadow-sm border-0 p-0">
                        <h5 class="font-weight-bold p-3 mb-0">Találatok</h5>
                        @foreach($customers as $customer)
                            <div class="customer py-2 px-4 action-hover-only"
                                 data-href="{{ action('CustomersController@show', $customer) }}">
                                <div class="row no-gutters">
                                    {{-- Ikonka --}}
                                    <div class="col-md-auto pr-3">
                                        @if(!$customer->is_reseller)
                                            <div class="d-flex justify-content-center align-items-center bg-info-pastel rounded-circle mt-2"
                                                 style="width: 32px; height: 32px;">
                                            <span class="icon text-info-pastel">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center align-items-center bg-success-pastel rounded-circle mt-2"
                                                 style="width: 32px; height: 32px;">
                                            <span class="icon text-success-pastel">
                                                <i class="fas fa-comments-dollar"></i>
                                            </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Név és Lakcím --}}
                                    <div class="col">
                                        <div class="customer-basics">
                                            <p class="mb-0 font-weight-bold">{{ $customer->name }}
                                                <small class="d-block">{{ $customer->address->getFormattedAddress() }}</small>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Telefonszám és E-mail --}}
                                    <div class="col-md-3 text-right">
                                        <p class="mb-0 mr-4">
                                            <span>{{ $customer->phone }}</span>
                                            <small class="d-block text-muted">{{ $customer->email }}</small>
                                        </p>
                                    </div>

                                    {{-- Gombok --}}
                                    <div class="col-md-auto text-right td-action">
                                        {{-- Szerkesztés --}}
                                        <a href="{{ action('CustomersController@edit', $customer) }}"
                                           class="btn btn-sm btn-muted p-1" data-toggle="tooltip" data-placement="top"
                                           title="Megrendelő szerkesztése">
                                            <span class="icon icon-sm">
                                                <i class="fas fa-pen"></i>
                                            </span>
                                        </a>

                                        {{-- Törlés --}}
                                        <form action="{{ action('CustomersController@delete', $customer) }}"
                                              class="d-inline-block" method="POST" data-toggle="tooltip"
                                              data-placement="top" title="Megrendelő törlése">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-del-customer btn-sm btn-muted px-1 py-0">
                                                <span class="icon icon-sm">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Paginátor --}}
                        @if (count($customers->links()->elements[0]) > 1)
                            <div class="pl-3 pt-3 border-top">
                                {{ $customers->appends(request()->except('page'))->links() }}
                            </div>
                        @endif
                    </div>
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
            // $('.tr-clickable').on('click', (e) => {
            //     if (e.currentTarget.dataset.redirectTo &&
            //         $(e.target).closest('.btn-del-customer').length === 0
            //     ) {
            //         window.location = e.currentTarget.dataset.redirectTo
            //     }
            // });

            $('.customer').on('click', (e) => {
                if (e.currentTarget.dataset.href &&
                    $(e.target).closest('.btn-del-customer').length === 0
                ) {
                    window.location = e.currentTarget.dataset.href;
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