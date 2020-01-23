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