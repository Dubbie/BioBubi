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
        <customers></customers>
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