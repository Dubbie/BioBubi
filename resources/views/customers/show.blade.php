@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelő részletek</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-teal mr-2">Új
                    megrendelő</a>
                <a href="{{ action('CustomersController@edit', $customer) }}"
                   class="btn btn-sm btn-teal shadow">Megrendelő szerkesztése</a>
            </div>
        </div>

        @include('inc.messages')

        {{-- Részletek --}}
        <div class="row">
            <div class="col-12">
                @if(!$customer->is_reseller)
                    @include('inc.customer_regular')
                @else
                    @include('inc.customer_reseller')
                @endif
            </div>
        </div>
    </div>

    {{-- Új teendő hozzáadása modul --}}
    @include('inc.modals.new_alert')

    {{-- Új termék hozzáfűzése a megrendelőhöz modal --}}
    @include('inc.modals.new_customer_items')

    {{-- Megjegyzés szerkesztés --}}
    @include('inc.modals.edit_comment')

    {{-- Megjegyzés szerkesztés --}}
    @include('inc.modals.edit_alert')
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            let elContainer = document.getElementById('customer-item-container');
            const elSum = document.getElementById('sum');
            const btnNewItem = document.getElementById('newCustomerItem');
            const spinner = document.getElementById('spinner');
            let customerItemCounter = 1;

            /**
             * Események bindelése
             */
            function bindAllElements() {
                // Beküldéskor spinner
                $('form').on('submit', (e) => {
                    e.stopPropagation();
                    const submitBtn = $(e.currentTarget).find('button[type=submit]');
                    submitBtn.prop('disabled', true);
                    submitBtn.addClass('disabled');
                    submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                });

                // Másolás
                $('.copyable').on('click', (e) => {
                    copyToClipboard(e.target.innerText);
                    $(e.target).tooltip('enable');
                    $(e.target).tooltip('show');
                    console.log('showing tooltip');

                    window.setTimeout(() => {
                        $(e.target).tooltip('hide');
                        $(e.target).tooltip('disable');
                        console.log('hiding tooltip');
                    }, 2500);
                });

                // Egyebek
                bindComments();
                bindCustomerItems();
                bindAlerts();
            }

            /**
             * Bebindeli a szükséges eseményeket a kommenthez
             */
            function bindComments() {
                // Megjegyzés szerkesztése
                $('.btn-edit-comment').on('click', (e) => {
                    document.getElementById('edit_comment_id').value = e.currentTarget.dataset.commentId;
                    document.getElementById('edit_message').value = e.currentTarget.dataset.commentMessage;
                });

                // Megjegyzés törlés
                $('.btn-del-comment').on('click', (e) => {
                    if (!confirm('Biztosan szeretnéd a kommentet törölni? Ez a folyamat nem visszafordítható.')) {
                        e.preventDefault();
                    }
                });
            }

            /**
             * Bebindeli a szükséges eseményeket a megrendelőhöz
             */
            function bindCustomerItems() {
                // Bindeljük be, ha a select változik akkor mi történjen
                $(document).on('change.counter', '.customer-item-id', (e) => {
                    const selectItem = e.target;

                    // Kiszedjük a jelenleg kiválasztott terméket
                    const selected = selectItem.options[selectItem.selectedIndex];
                    const priceInput = $(selectItem).closest('.customer-item').find('input.customer-item-price')[0];
                    const qtyInput = $(selectItem).closest('.customer-item').find('input.customer-item-quantity')[0];

                    priceInput.value = selected.dataset.price;
                    qtyInput.value = 1;

                    updateSum();
                });

                // Bindeljük, ha ár változik akkor frissítünk, ha mennyiség akkor is
                $(document).on('input.counter', '.customer-item-price', () => {
                    updateSum();
                });
                $(document).on('input.counter', '.customer-item-quantity', () => {
                    updateSum();
                });

                // Új sor a rögzítendő tárgyakhoz
                $(btnNewItem).on('click', () => {
                    addNewItemInputs();
                });

                // Új rögzítendő tárgy törlése
                $(document).on('click.counter', '.btn-del-customer-item', (e) => {
                    const selectItem = e.target;
                    const customerItemRow = $(selectItem).closest('.customer-item')[0];
                    const parent = customerItemRow.parentNode;
                    parent.removeChild(customerItemRow);

                    customerItemCounter--;

                    updateSum();
                });

                // Törlési biztonsági
                $('.btn-del-purchase').on('click', (e) => {
                    if (!confirm('Biztosan törölni szeretnéd a rögzített vásárlást a termékről? Ez a folyamat nem visszafordítható!')) {
                        e.preventDefault();
                    }
                });

                // Form előtt a rejtett checkboxokat tiltsuk le
                $('#item-chooser').on('submit', (e) => {
                    $('.checkbox-completed').each((i, el) => {
                        if (el.checked) {
                            $(el).prev().attr('disabled', true);
                        }
                    });
                });
            }

            /**
             * Bebindeli a szükséges eseményeket a teendőkhöz
             */
            function bindAlerts() {
                // Új teendő
                $('.btn-new-alert').on('click', (e) => {
                    document.getElementById('new_alert_comment_id').value = e.currentTarget.dataset.commentId;
                });

                // Teendő törlési biztonsági
                $('.btn-del-alert').on('click', (e) => {
                    if (!confirm('Biztosan törölni szeretnéd a teendőt? Ez a folyamat nem visszafordítható!')) {
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
            }

            /**
             * Betölt egy új rögzítendő terméket.
             */
            function addNewItemInputs() {
                customerItemCounter++;

                btnNewItem.classList.add('disabled');
                btnNewItem.disabled = true;
                $(spinner).show();

                fetch('{{ action('CustomerItemsController@loadNew') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({count: customerItemCounter})
                }).then((response) => {
                    return response.text();
                }).then((html) => {
                    $(elContainer).append(html);

                    // Inicializáljuk az új dátum időpont választót
                    initDateTimePicker('date_' + customerItemCounter);
                    enableTooltips();
                }).finally(() => {
                    btnNewItem.classList.remove('disabled');
                    btnNewItem.disabled = false;

                    $(spinner).hide();
                });
            }

            /**
             * Frissíti az összegszámlálót.
             */
            function updateSum() {
                // Megkeressük az összes termék árát
                let sum = 0;
                let prices = [];
                let quantities = [];

                $('.customer-item-price').each((i, el) => {
                    prices.push(el.value);
                });

                $('.customer-item-quantity').each((i, el) => {
                    quantities.push(el.value);
                });

                // Matek
                for (let i = 0; i < prices.length; i++) {
                    sum += prices[i] * quantities[i];
                }

                elSum.innerText = sum.toString();
            }

            /**
             * Kimásolja a megadott szöveget a vágólapra.
             */
            function copyToClipboard(str) {
                const el = document.createElement('textarea');  // Create a <textarea> element
                el.value = str;                                 // Set its value to the string that you want copied
                el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
                el.style.position = 'absolute';
                el.style.left = '-9999px';                      // Move outside the screen to make it invisible
                document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
                const selected =
                    document.getSelection().rangeCount > 0        // Check if there is any content selected previously
                        ? document.getSelection().getRangeAt(0)     // Store selection if found
                        : false;                                    // Mark as false to know no selection existed before
                el.select();                                    // Select the <textarea> content
                document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
                document.body.removeChild(el);                  // Remove the <textarea> element
                if (selected) {                                 // If a selection existed before copying
                    document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
                    document.getSelection().addRange(selected);   // Restore the original selection
                }
            }

            /**
             * Inicializálja a date time választót a megadott ID alapján.
             * @param id
             */
            function initDateTimePicker(id) {
                $('#' + id).datetimepicker({
                    dayOfWeekStart: 1,
                });
            }

            /**
             * Bekapcsolja a tooltipeket
             */
            function enableTooltips() {
                $('.copyable').tooltip({
                    trigger: 'manual'
                });
                $('.has-tooltip').tooltip({
                    trigger: 'hover'
                });
            }

            /**
             * Konstruktor szerű
             */
            function init() {
                bindAllElements();
                updateSum();

                // Tooltipes basz
                enableTooltips();

                // Datetime picker
                $.datetimepicker.setLocale('hu');
                initDateTimePicker('date_1');

                // Teendős datetime picker
                initDateTimePicker('new_alert_time');
                initDateTimePicker('edit_alert_time');
            }

            init();
        });
    </script>
@endsection