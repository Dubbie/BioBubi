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