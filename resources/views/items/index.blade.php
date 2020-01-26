@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Termékek</h1>
            <div class="btn-toolbar">
                <button type="button" class="btn btn-sm btn-teal shadow" data-toggle="modal"
                        data-target="#newItemModal">
                    Új termék
                </button>
            </div>
        </div>

        {{-- Megrendelők --}}
        @if(count($items) > 0)
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-body border-0 shadow-sm">
                        <table class="table table-borderless mb-0">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Név</th>
                                <th scope="col" class="text-right">Ár</th>
                                <th scope="col" class="text-right">Eladva</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr class="action-hover-only"
                                    data-redirect-to="{{ action('ItemsController@show', $item) }}">
                                    <td class="text-muted">{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-right">{{ $item->getFormattedPrice(true) }}</td>
                                    <td class="text-right">
                                        @php
                                            $count = 0;

                                            foreach($item->purchases as $purchase) {
                                                $count += $purchase->quantity;
                                            }
                                        @endphp

                                        {{ $count . 'db' }}
                                    </td>
                                    <td class="td-action text-right">
                                        {{-- Termék szerkesztése --}}
                                        <span data-toggle="tooltip" data-placement="top" title="Termék szerkesztése">
                                            <button class="btn btn-edit-item btn-sm btn-muted px-1 py-0" data-toggle="modal"
                                                    data-target="#editItemModal"
                                                    data-item-id="{{ $item->id }}"
                                                    data-item-name="{{ $item->name }}"
                                                    data-item-price="{{ $item->price }}">
                                                <span class="icon icon-sm">
                                                    <i class="fas fa-pen"></i>
                                                </span>
                                            </button>
                                        </span>

                                        {{-- Termék tölése --}}
                                        <form action="{{ action('ItemsController@delete', $item) }}" class="d-inline-block"
                                              method="POST" data-toggle="tooltip" data-placement="top" title="Termék törlése">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-del-item btn-sm btn-muted px-1 py-0">
                                            <span class="icon icon-sm">
                                                <i class="fas fa-times"></i>
                                            </span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <p class="lead mb-0">Az itt szereplő termékek azok, amiket akkor látsz, ha új vásárlást
                            rögzítesz egy felhasználóhoz.</p>
                    </div>
                </div>
            </div>
        @else
            <p class="lead mb-2">Nincsen még termék az adatbázisban.</p>
            <p>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#newItemModal">
                    Termék hozzáadása
                </button>
            </p>
        @endif
    </div>

    {{-- Új termék modal --}}
    <div class="modal fade" id="newItemModal" tabindex="-1" role="dialog" aria-labelledby="newItemModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-new-item" action="{{ action('ItemsController@store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newItemModalLabel">Új termék</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row align-items-end">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name">Termék megnevezése</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Ár</label>
                                    <div class="input-group mb-3">
                                        <input type="tel" id="price" name="price" class="form-control"
                                               aria-label="Termék ára" aria-describedby="basic-addon2" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Ft</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">Hozzáadás</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Szerkesztő modal --}}
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-edit-item" action="{{ action('ItemsController@update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_item_id" name="edit_item_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Termék szerkesztése</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row align-items-end">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name">Termék megnevezése</label>
                                    <input type="text" id="edit_name" name="edit_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_price">Ár</label>
                                    <div class="input-group mb-3">
                                        <input type="tel" id="edit_price" name="edit_price" class="form-control"
                                               aria-label="Termék ára" aria-describedby="basic-addon2" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Ft</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">Frissítés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            const inputEditItemId = document.getElementById('edit_item_id');
            const inputEditItemName= document.getElementById('edit_name');
            const inputEditItemPrice = document.getElementById('edit_price');

            /**
             * Tárgy szerkesztés
             */
            $('.btn-edit-item').on('click', (e) => {
                inputEditItemId.value = e.currentTarget.dataset.itemId;
                inputEditItemName.value = e.currentTarget.dataset.itemName;
                inputEditItemPrice.value = e.currentTarget.dataset.itemPrice;
            });

            /**
             * Tárgy törlés
             */
            $('.btn-del-item').on('click', (e) => {
                if (!confirm('Biztosan törölni szeretnéd a terméket? Ez a folyamat nem visszafordítható.')) {
                    e.preventDefault();
                }
            });

            $('form').on('submit', (e) => {
                e.stopPropagation();
                const submitBtn = $(e.currentTarget).find('button[type=submit]');
                submitBtn.prop('disabled', true);
                submitBtn.addClass('disabled');
                submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            });

            // Tooltipek
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection