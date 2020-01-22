<div id="customer-item-container">
    <div class="form-row align-items-end">
        <div class="col">
            <div class="form-group">
                <label for="item_id_{{ $count }}">Termék</label>
                <select name="item_id[]" id="item_id_{{ $count }}" class="custom-select customer-item-id">
                    <option value="" disabled selected hidden>Válassz terméket...</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="date_{{ $count }}">Időpont</label>
                <input type='text' class="form-control" id="date_{{ $count }}" name="date[]">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="price_{{ $count }}">Ár</label>
                <div class="input-group mb-0">
                    <input type="tel" id="price_{{ $count }}" name="price[]" class="form-control customer-item-price"
                           aria-label="Termék ára" aria-describedby="basic-addon2" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Ft</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="quantity_{{ $count }}">Mennyiség</label>
                <div class="input-group mb-0">
                    <input type="number" id="quantity_{{ $count }}" name="quantity[]" class="form-control customer-item-quantity"
                           aria-label="Termék ára" aria-describedby="basic-addon2" min="1" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">db</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button type="button" class="btn-del-customer-item btn btn-muted">
                    <span class="icon">
                        <i class="far fa-times-circle"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>