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
                                            <span class="font-weight-bold">{{ $purchase->quantity }}db</span>
                                            <span>{{ $purchase->getItemName() }}</span>
                                            @if($purchase->date)
                                                <small class="d-block text-small">{{ $purchase->date->format('Y M d, H:i') }}</small> @endif
                                        </span>
                                    <span class="h5 mb-0 item-price">{{ number_format($purchase->price * $purchase->quantity, 0, '.', ' ') }}
                                        <small class="font-weight-bold">Ft</small>
                                            <form class="d-inline-block"
                                                  action="{{ action('CustomerItemsController@delete', $purchase) }}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-del-purchase btn btn-muted btn-sm px-1 py-0">
                                                    <span class="icon">
                                                        <i class="far fa-times-circle"></i>
                                                    </span>
                                                </button>
                                            </form>
                                        </span>
                                    </div>
                                @endforeach

                                {{-- Összegző --}}
                                <div class="d-flex justify-content-between align-items-baseline border-top mt-2 pt-2">
                                    <span class="mr-2">Összesen: </span>
                                    <h3 class="font-weight-bold">{{ number_format($total, 0, '.', ' ') }}
                                        <small class="font-weight-bold">Ft</small>
                                    </h3>
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
                                                @if($comment->user_id == Auth::id())
                                                    {{-- Szerkesztés gomb --}}
                                                    <button type="button" class="btn btn-edit-comment btn-sm btn-muted px-1 py-0"
                                                            data-toggle="modal"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-comment-message="{{ $comment->message }}"
                                                            data-target="#editCommentModal">
                                                        <span class="icon">
                                                            <i class="fas fa-pen"></i>
                                                        </span>
                                                    </button>

                                                    {{-- Törlés gomb--}}
                                                    <form action="{{ action('CommentsController@delete', $comment) }}" class="d-inline-block mr-2" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-del-comment btn-muted px-1 py-0">
                                                            <span class="icon">
                                                                <i class="far fa-times-circle"></i>
                                                            </span>
                                                        </button>
                                                    </form>
                                                @endif
                                                <small class="text-muted">{{ $comment->updated_at->format('Y M d, H:i:s') }}</small>
                                            </div>
                                        </div>
                                        <div class="lead comment-message">{{ $comment->message }}</div>
                                    </div>
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

    {{-- Új termék hozzáfűzése a megrendelőhöz modal --}}
    <div class="modal fade" id="newCustomerItemsModal" tabindex="-1" role="dialog"
         aria-labelledby="newCustomerItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ action('CustomerItemsController@store') }}" id="item-chooser" method="POST"
                      autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newCustomerItemsModalLabel">Rögzítendő termékek</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div id="customer-item-container">
                            <div class="form-row align-items-end">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="item_id_1">Termék</label>
                                        <select name="item_id[]" id="item_id_1" class="custom-select customer-item-id">
                                            <option value="" disabled selected hidden>Válassz terméket...</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}"
                                                        data-price="{{ $item->price }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_1">Időpont</label>
                                        <input type='text' class="form-control" id="date_1" name="date[]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="price_1">Ár</label>
                                        <div class="input-group mb-0">
                                            <input type="tel" id="price_1" name="price[]"
                                                   class="form-control customer-item-price"
                                                   aria-label="Termék ára" aria-describedby="basic-addon2" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">Ft</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="quantity_1">Mennyiség</label>
                                        <div class="input-group mb-0">
                                            <input type="tel" id="quantity_1" name="quantity[]"
                                                   class="form-control customer-item-quantity"
                                                   aria-label="Termék ára" aria-describedby="basic-addon2" min="1"
                                                   required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">db</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    {{-- Action gombok mennek ide --}}
                                </div>
                            </div>
                        </div>

                        {{-- Összegzés --}}
                        <div class="row align-items-end">
                            <div class="col">
                                <button id="newCustomerItem" type="button"
                                        class="btn btn-link btn-sm px-0 pb-0 text-decoration-none">Rögzítendő termék
                                    hozzáadása
                                </button>
                            </div>
                            <div class="col text-right">
                                <p class="h2 font-weight-light mb-0">
                                    <span id="sum">0</span>
                                    <small class="font-weight-bold" style="font-size: 16px">Ft</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-end">
                        <div>
                            <a href="{{ action('ItemsController@create') }}"
                               class="btn btn-sm btn-link text-muted pl-0">Hiányzó termék hozzáadása</a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">Termékek rögzítése</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Megjegyzés szerkesztés --}}
    <div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog"
         aria-labelledby="editCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ action('CommentsController@update') }}" method="POST"
                      autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_comment_id" name="edit_comment_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCommentModalLabel">Megjegyzés szerkesztése</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_message">Üzenet</label>
                            <textarea name="edit_message" id="edit_message" class="form-control" cols="30"
                                      rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">Megjegyzés frissítése</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            let elContainer = document.getElementById('customer-item-container');
            const elSum = document.getElementById('sum');
            const btnNewItem = document.getElementById('newCustomerItem');
            let customerItemCounter = 1;

            /**
             * Események bindelése
             */
            function bindAllElements() {
                $('.btn-edit-comment').on('click', (e) => {
                    document.getElementById('edit_comment_id').value = e.currentTarget.dataset.commentId;
                    document.getElementById('edit_message').value = e.currentTarget.dataset.commentMessage;
                });

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

                // Megjegyzés törlés
                $('.btn-del-comment').on('click', (e) => {
                    if (!confirm('Biztosan szeretnéd a kommentet törölni? Ez a folyamat nem visszafordítható.')) {
                        e.preventDefault();
                    }
                });
            }

            /**
             * Betölt egy új rögzítendő terméket.
             */
            function addNewItemInputs() {
                customerItemCounter++;

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
             * Konstruktor szerű
             */
            function init() {
                bindAllElements();
                updateSum();

                // Tooltipes basz
                $('.tooltip').tooltip({
                    trigger: 'manual'
                });

                // Datetime picker
                $.datetimepicker.setLocale('hu');
                initDateTimePicker('date_1');
            }

            init();
        });
    </script>
@endsection