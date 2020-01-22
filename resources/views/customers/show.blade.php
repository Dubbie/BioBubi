@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelő részletek</h1>
            <div class="btn-toolbar">
                <a href="{{ action('CustomersController@index') }}" class="btn btn-sm btn-link mr-2 text-decoration-none">Vissza</a>
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-primary">Új
                    megrendelő</a>
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
                               class="lead mb-2 copyable">{{ $customer->name }}</p>
                            <small class="d-block font-weight-bold">Telefonszám</small>
                            <p id="customer-{{ $customer->id }}-phone"
                               class="lead mb-2 copyable">{{ $customer->phone }}</p>
                            <small class="d-block font-weight-bold">Lakcím</small>
                            <p id="customer-{{ $customer->id }}-address"
                               class="lead mb-2 copyable">{{ $customer->address->getFormattedAddress() }}</p>
                            <small class="d-block font-weight-bold">E-mail</small>
                            <p id="customer-{{ $customer->id }}-email"
                               class="lead mb-0 copyable">{{ $customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            @if(count($customer->purchases) > 0)
                                <h5 class="font-weight-bold">Megvásárolt termékek:</h5>
                                @foreach($customer->purchases as $purchase)
                                    <div class="mb-0 d-flex justify-content-between">
                                        <span class="h5 item-name font-weight-light"><span class="font-weight-bold">{{ $purchase->quantity }}db</span> {{ $purchase->item ? $purchase->item->name : 'Törölt termék' }}</span>
                                        <span class="h5 item-price">{{ $purchase->price * $purchase->quantity }}<small class="font-weight-bold">Ft</small>
                                            <form class="d-inline-block" action="{{ action('CustomerItemsController@delete', $purchase) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-del-purchase btn btn-muted btn-sm px-1">
                                                    <span class="icon">
                                                        <i class="far fa-times-circle"></i>
                                                    </span>
                                                </button>
                                            </form>
                                        </span>
                                    </div>
                                @endforeach

                                {{-- Összegző --}}
                                <div class="d-flex justify-content-between align-items-baseline border-top pt-2">
                                    <span class="mr-2">Összesen: </span>
                                    <h3 class="font-weight-bold">{{ $total }}<small class="font-weight-bold">Ft</small></h3>
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
                               class="lead mb-0 copyable">{{ $customer->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Telefonszám</small>
                            <p id="customer-{{ $customer->id }}-phone"
                               class="lead mb-0 copyable">{{ $customer->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Lakcím</small>
                            <p id="customer-{{ $customer->id }}-address"
                               class="lead mb-0 copyable">{{ $customer->address->getFormattedAddress() }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">E-mail</small>
                            <p id="customer-{{ $customer->id }}-email"
                               class="lead mb-0 copyable">{{ $customer->email }}</p>
                        </div>
                    @endif
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="font-weight-bold mb-0">Megjegyzések</p>
                        @if(count($customer->comments) > 0)
                            @foreach($customer->comments as $comment)
                                <div class="comment">
                                    <div class="comment-header d-flex justify-content-between align-items-start">
                                        <p class="h5 font-weight-bold mb-0">{{ $comment->author->name }}</p>
                                        <small class="text-muted">{{ $comment->created_at->format('Y M d, H:i:s') }}</small>
                                    </div>
                                    <div class="lead comment-message text-muted">{{ $comment->message }}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="lead">A megrendelőhöz még nem fűzött hozzá senki semmit.</p>
                        @endif
                        <form action="{{ action('CommentsController@store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}" required>
                            <div class="form-group mt-2">
                                <label for="message" ><small class="font-weight-bold">Új komment</small></label>
                                <textarea name="message" id="message" cols="30" rows="5" class="form-control"
                                          required></textarea>
                            </div>
                            <div class="form-group text-right mb-0">
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
                                            <input type="number" id="quantity_1" name="quantity[]"
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
                                        class="btn btn-link btn-sm px-0 pb-0 text-decoration-none">Rögzítendő termék hozzáadása
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
                            <a href="{{ action('ItemsController@create') }}" class="btn btn-sm btn-link text-muted pl-0">Hiányzó termék hozzáadása</a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás</button>
                            <button type="submit" class="btn btn-sm btn-success">Termékek rögzítése</button>
                        </div>
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

            function bindAllElements() {
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
            }

            function addNewItemInputs() {
                // Húbazdmeg
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
                    //
                    // inputPrices = document.querySelectorAll('.customer-item-price');
                    // $(inputPrices).each((i, el) => {
                    //     console.log(el);
                    // });
                })
            }

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

            function init() {
                bindAllElements();
                updateSum();
            }

            init();
        });
    </script>
@endsection