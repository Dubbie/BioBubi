<div class="modal fade" id="newAlertModal" tabindex="-1" role="dialog"
     aria-labelledby="newAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ action('AlertsController@store') }}" id="form-new-alert" method="POST"
                  autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newAlertModalLabel">Új teendő hozzáadása</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="new_alert_comment_id" name="new_alert_comment_id" value="">
                    <div class="form-group">
                        <label for="new_alert_message">Üzenet</label>
                        <textarea name="new_alert_message" id="new_alert_message" cols="30" rows="1" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="new_alert_time">Időpont</label>
                        <input type="text" name="new_alert_time" id="new_alert_time" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-link text-muted" data-dismiss="modal">Bezárás
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">Teendő hozzáadása</button>
                </div>
            </form>
        </div>
    </div>
</div>