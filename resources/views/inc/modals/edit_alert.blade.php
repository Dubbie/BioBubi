<div class="modal fade" id="editAlertModal" tabindex="-1" role="dialog"
     aria-labelledby="editAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ action('AlertsController@update') }}" id="form-new-alert" method="POST"
                  autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editAlertModalLabel">Teendő szerkesztése</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_alert_id" name="edit_alert_id" value="">
                    <div class="form-group">
                        <label for="edit_alert_message">Üzenet</label>
                        <textarea name="edit_alert_message" id="edit_alert_message" cols="30" rows="1" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_alert_time">Időpont</label>
                        <input type="text" name="edit_alert_time" id="edit_alert_time" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">Teendő frissítése</button>
                </div>
            </form>
        </div>
    </div>
</div>