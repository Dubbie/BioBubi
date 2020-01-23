@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelő részletek</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-primary mr-2">Új
                    megrendelő</a>
                <a href="{{ action('CustomersController@edit', $customer) }}"
                   class="btn btn-sm btn-primary">Megrendelő szerkesztése</a>
            </div>
        </div>

        @include('inc.messages')

        {{-- Részletek --}}
        <div class="row">
            <div class="col-12">

                <div class="row">
                    @if(!$customer->is_reseller)
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Név</small>
                            <p id="customer-{{ $customer->id }}-name"
                               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
                               title="Név kimásolva">{{ $customer->name }}</p>
                            <small class="d-block font-weight-bold">Telefonszám</small>
                            <p id="customer-{{ $customer->id }}-phone"
                               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
                               title="Telefonszám kimásolva">{{ $customer->phone }}</p>
                            <small class="d-block font-weight-bold">Lakcím</small>
                            <p id="customer-{{ $customer->id }}-address"
                               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
                               title="Lakcím kimásolva">{{ $customer->address->getFormattedAddress() }}</p>
                            <small class="d-block font-weight-bold">E-mail</small>
                            <p id="customer-{{ $customer->id }}-email"
                               class="d-inline-block lead mb-0 copyable" data-toggle="tooltip" data-placement="right"
                               title="E-mail kimásolva">{{ $customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            @if(count($customer->purchases) > 0)
                                <h5 class="font-weight-bold">Megvásárolt termékek:</h5>
                                @foreach($customer->purchases as $purchase)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="h5 mb-0 item-name font-weight-light">
                                            <span>{{ $purchase->quantity }} × {{ $purchase->getItemName() }}</span>
                                            @if($purchase->completed)
                                                <small class="ml-2 text-success">Teljesítve</small>
                                            @endif
                                            <small class="d-block text-small">
                                                @if($purchase->date)
                                                    {{ $purchase->date->format('Y M d, H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </small>
                                        </span>

                                        <div class="purchase-action">
                                            <div class="action d-inline-flex align-items-start mr-2">
                                                {{--  Teljesítés --}}
                                                @if(!$purchase->completed)
                                                    <form class="d-inline-flex has-tooltip"
                                                          action="{{ action('CustomerItemsController@complete', $purchase) }}"
                                                          method="POST"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Rögzített vásárlás teljesítése">
                                                        @csrf
                                                        <button class="btn-complete-purchase btn btn-muted btn-sm px-1 py-0">
                                                        <span class="icon">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Törlés --}}
                                                <form class="d-inline-flex has-tooltip"
                                                      action="{{ action('CustomerItemsController@delete', $purchase) }}"
                                                      method="POST"
                                                      data-toggle="tooltip" data-placement="top"
                                                      title="Rögzített vásárlás törlése">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn-del-purchase btn btn-muted btn-sm px-1 py-0">
                                                        <span class="icon">
                                                            <i class="fas fa-times"></i>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                            <span class="h5 mb-0 item-price">{{ number_format($purchase->price * $purchase->quantity, 0, '.', ' ') }}
                                                <small class="font-weight-bold">Ft</small>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Összegző --}}
                                <div class="d-flex justify-content-between align-items-baseline border-top mt-2 pt-2">
                                    <h4 class="font-weight-bold">Összesen: </h4>
                                    <h4 class="font-weight-bold">{{ number_format($total, 0, '.', ' ') }}
                                        <small class="font-weight-bold">Ft</small>
                                    </h4>
                                </div>

                                {{-- További vásárlások rögzítése --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                            data-target="#newCustomerItemsModal">További vásárlások rögzítése
                                    </button>
                                </div>
                            @else
                                <p class="lead font-weight-bold mb-0">Az ügyfél még nem vásárolt termékeket.</p>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#newCustomerItemsModal">Vásárlások rögzítése
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Név</small>
                            <p id="customer-{{ $customer->id }}-name"
                               class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                               title="Név kimásolva">{{ $customer->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Telefonszám</small>
                            <p id="customer-{{ $customer->id }}-phone"
                               class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                               title="Telefonszám kimásolva">{{ $customer->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Lakcím</small>
                            <p id="customer-{{ $customer->id }}-address"
                               class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                               title="Lakcím kimásolva">{{ $customer->address->getFormattedAddress() }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">E-mail</small>
                            <p id="customer-{{ $customer->id }}-email"
                               class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                               title="E-mail kimásolva">{{ $customer->email }}</p>
                        </div>
                    @endif
                </div>

                {{-- Megjegyzések --}}
                <div class="row mt-4">
                    <div class="col-12">

                        <h3 class="font-weight-bold mb-0">Megjegyzések</h3>
                        @if(count($customer->comments) > 0)
                            <div class="comments-container mt-2">
                                @foreach($customer->comments as $comment)
                                    <div class="comment" style="border-left-color: {{ $comment->author->color }}">
                                        <div class="comment-header d-flex justify-content-between align-items-start">
                                            <p class="h5 font-weight-bold mb-0">{{ $comment->author->name }}</p>
                                            <div class="action">
                                                {{-- Új értesítő beállítása --}}
                                                <span class="has-tooltip" data-toggle="tooltip" data-placement="top"
                                                      title="Új teendő hozzáadása">
                                                    <button class="btn btn-new-alert btn-sm btn-muted px-1 py-0"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-toggle="modal"
                                                            data-target="#newAlertModal">
                                                        <span class="icon">
                                                            <i class="fas fa-bell"></i>
                                                        </span>
                                                    </button>
                                                </span>

                                                @if($comment->user_id == Auth::id())
                                                    {{-- Szerkesztés gomb --}}
                                                    <span class="has-tooltip" data-toggle="tooltip" data-placement="top"
                                                          title="Megjegyzés szerkesztése">
                                                        <button type="button"
                                                                class="btn btn-edit-comment btn-sm btn-muted px-1 py-0"
                                                                data-toggle="modal"
                                                                data-comment-id="{{ $comment->id }}"
                                                                data-comment-message="{{ $comment->message }}"
                                                                data-target="#editCommentModal">
                                                            <span class="icon">
                                                                <i class="fas fa-pen"></i>
                                                            </span>
                                                        </button>
                                                    </span>


                                                    {{-- Törlés gomb--}}
                                                    <form action="{{ action('CommentsController@delete', $comment) }}"
                                                          class="d-inline-block has-tooltip mr-2" method="POST" data-toggle="tooltip" data-placement="top"
                                                          title="Megjegyzés törlése">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-del-comment btn-muted px-1 py-0">
                                                            <span class="icon">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                                        </button>
                                                    </form>
                                                @endif
                                                <small class="text-muted">{{ $comment->updated_at->format('Y M d, H:i:s') }}</small>
                                            </div>
                                        </div>
                                        <div class="lead comment-message">{{ $comment->message }}</div>
                                    </div>

                                    {{-- Teendők ha vannak --}}
                                    @if(count($comment->alerts) > 0)
                                        <div class="mb-4">
                                            @foreach($comment->alerts as $alert)
                                                <div class="d-flex justify-content-between action-hover-only py-2 px-4
                                                    @if($alert->completed) alert-completed
                                                    @elseif($alert->isOverdue()) alert-overdue
                                                    @endif">
                                                    {{-- Teendő leírás --}}
                                                    <div class="alert-details">
                                                        <p class="mb-0">
                                                            <small class="font-weight-bold"
                                                                   style="line-height: 1; color: {{ $alert->user->color }}">{{ $alert->user->name }}</small>
                                                            <small class="has-tooltip font-weight-bold ml-2"
                                                                   data-toggle="tooltip"
                                                                   data-placement="right"
                                                                   title="{{ $alert->time->format('Y M d, H:i') }}">{{ $alert->getRemainingLabel() }}</small>
                                                            <span class="d-block font-weight-bold"
                                                                  style="line-height: 1">{{ $alert->message }}</span>
                                                        </p>
                                                    </div>

                                                    {{-- Teendő akciók --}}
                                                    <div class="td-action alert-actions">
                                                        {{-- Teljesítés --}}
                                                        @if(!$alert->completed)
                                                            <form action="{{ action('AlertsController@complete', $alert) }}"
                                                                  class="d-inline-block has-tooltip"
                                                                  data-toggle="tooltip" data-placement="top"
                                                                  method="POST"
                                                                  title="Teendő teljesítése">
                                                                @csrf
                                                                <button class="btn btn-complete-alert btn-sm btn-link btn-muted px-1 py-0">
                                                                <span class="icon">
                                                                    <i class="fas fa-check"></i>
                                                                </span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        {{-- Módosítás --}}
                                                        <span class="has-tooltip" data-toggle="tooltip"
                                                              data-placement="top"
                                                              title="Teendő szerkesztése">
                                                            <button type="button"
                                                                    class="btn btn-edit-alert btn-sm btn-link btn-muted px-1 py-0"
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
                                                        <form action="{{ action('AlertsController@delete', $alert) }}"
                                                              class="d-inline-block has-tooltip"
                                                              method="POST" data-toggle="tooltip" data-placement="top"
                                                              title="Teendő törlése">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-del-alert btn-sm btn-link btn-muted px-1 py-0">
                                                                <span class="icon">
                                                                    <i class="fas fa-times"></i>
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </div> {{-- Módósítás, Törlés, Teljesítés vége --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="lead">A megrendelőhöz még nem fűzött hozzá senki semmit.</p>
                        @endif
                        <form action="{{ action('CommentsController@store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}" required>
                            <div class="form-group mt-2">
                                <label for="message">
                                    <small class="font-weight-bold">Új komment</small>
                                </label>
                                <textarea name="message" id="message" cols="30" rows="5" class="form-control"
                                          required></textarea>
                            </div>
                            <div class="form-group d-flex justify-content-between mb-0">
                                <a href="{{ action('CustomersController@index') }}"
                                   class="btn btn-sm btn-link pl-0 text-decoration-none">Vissza</a>
                                <button type="submit" class="btn btn-sm btn-success">Beküldés</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                    const priceInput = $(selectItem).closest('.form-row').find('input.customer-item-price')[0];
                    const qtyInput = $(selectItem).closest('.form-row').find('input.customer-item-quantity')[0];

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
                    const customerItemRow = $(selectItem).closest('.form-row')[0];
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