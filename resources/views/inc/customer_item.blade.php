<div class="customer-item">
    <div class="form-row align-items-end mt-4 mb-2">
        <div class="col">
            <div class="form-group mb-0">
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
            <div class="form-group mb-0">
                <label for="date_{{ $count }}">Időpont</label>
                <input type='text' class="form-control" id="date_{{ $count }}" name="date[]">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-0">
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
            <div class="form-group mb-0">
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
            <div class="form-group mb-0">
                <button type="button" class="btn-del-customer-item btn btn-muted has-tooltip" data-toggle="tooltip" data-placement="top" title="Rögzítendő termék törlése">
                    <span class="icon icon-sm">
                        <i class="fas fa-times"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="form-group mb-0">
        <div class="custom-control custom-checkbox">
            <input type="hidden" class="checkbox-completed-hidden" name="completed[]" value="off">
            <input type="checkbox" class="checkbox-completed custom-control-input" name="completed[]" id="completed_{{ $count }}">
            <label class="custom-control-label" for="completed_{{ $count }}">Teljesítve</label>
        </div>
    </div>
</div>