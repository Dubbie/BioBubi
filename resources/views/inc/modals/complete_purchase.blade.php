<div class="modal fade" id="completePurchaseModal" tabindex="-1" role="dialog"
     aria-labelledby="completePurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ action('CustomerItemsController@complete') }}" id="form-complete-purchase" method="POST"
                  autocomplete="off">
                @csrf
                @method('POST')
                <input type="hidden" id="complete_purchase_id" name="complete_purchase_id"/>
                <div class="modal-header">
                    <h5 class="modal-title" id="completePurchaseModalLabel">Megvásárolt termék teljesítése</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Ha szeretnél a megvásárolt termékhez időpontot rögzíteni, töltsd ki az alábbi mezőt. (Opcionális)</p>
                    <div class="form-group">
                        <label for="complete_purchase_date" class="text-small">Időpont</label>
                        <input type="text" name="complete_purchase_date" id="complete_purchase_date" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">Teljesítés</button>
                </div>
            </form>
        </div>
    </div>
</div>