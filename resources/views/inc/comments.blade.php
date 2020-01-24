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
                                {{-- Új értesítő beállítása --}}
                                <span class="has-tooltip" data-toggle="tooltip" data-placement="top"
                                      title="Új teendő hozzáadása">
                                                    <button class="btn btn-new-alert btn-sm btn-muted px-1 py-0"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-toggle="modal"
                                                            data-target="#newAlertModal">
                                                        <span class="icon icon-sm">
                                                            <i class="fas fa-bell"></i>
                                                        </span>
                                                    </button>
                                                </span>

                                @if($comment->user_id == Auth::id())
                                    {{-- Szerkesztés gomb --}}
                                    <span class="has-tooltip" data-toggle="tooltip" data-placement="top"
                                          title="Megjegyzés szerkesztése">
                                                        <button type="button"
                                                                class="btn btn-edit-comment btn-sm btn-muted px-1 py-0"
                                                                data-toggle="modal"
                                                                data-comment-id="{{ $comment->id }}"
                                                                data-comment-message="{{ $comment->message }}"
                                                                data-target="#editCommentModal">
                                                            <span class="icon icon-sm">
                                                                <i class="fas fa-pen"></i>
                                                            </span>
                                                        </button>
                                                    </span>


                                    {{-- Törlés gomb--}}
                                    <form action="{{ action('CommentsController@delete', $comment) }}"
                                          class="d-inline-block has-tooltip mr-2" method="POST" data-toggle="tooltip" data-placement="top"
                                          title="Megjegyzés törlése">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-del-comment btn-muted px-1 py-0">
                                                            <span class="icon icon-sm">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                        </button>
                                    </form>
                                @endif
                                <small class="text-muted has-tooltip"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ $comment->updated_at->translatedFormat('Y F d, H:i:s') }}">
                                    {{ $comment->updated_at->shortRelativeToNowDiffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div class="lead comment-message">{{ $comment->message }}</div>
                    </div>

                    {{-- Teendők ha vannak --}}
                    @if(count($comment->alerts) > 0)
                        <div class="mb-4">
                            @foreach($comment->alerts as $alert)
                                <div class="d-flex justify-content-between action-hover-only py-2 px-4
                                                    @if($alert->completed) alert-completed
                                                    @endif">
                                    {{-- Teendő leírás --}}
                                    <div class="alert-details">
                                        <p class="mb-0">
                                            <small class="font-weight-bold"
                                                   style="line-height: 1; color: {{ $alert->user->color }}">{{ $alert->user->name }}</small>
                                            <small class="has-tooltip font-weight-bold ml-2"
                                                   data-toggle="tooltip"
                                                   data-placement="right"
                                                   title="{{ $alert->time->translatedFormat('Y F d, H:i') }}">{!! $alert->getStatusBadge() !!}</small>
                                            <span class="d-block font-weight-bold"
                                                  style="line-height: 1">{{ $alert->message }}</span>
                                        </p>
                                    </div>

                                    {{-- Teendő akciók --}}
                                    <div class="td-action alert-actions">
                                        {{-- Teljesítés --}}
                                        @if(!$alert->completed)
                                            <form action="{{ action('AlertsController@complete', $alert) }}"
                                                  class="d-inline-block has-tooltip"
                                                  data-toggle="tooltip" data-placement="top"
                                                  method="POST"
                                                  title="Teendő teljesítése">
                                                @csrf
                                                <button class="btn btn-complete-alert btn-sm btn-link btn-muted px-1 py-0">
                                                                <span class="icon icon-sm">
                                                                    <i class="fas fa-check"></i>
                                                                </span>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- Módosítás --}}
                                        <span class="has-tooltip" data-toggle="tooltip"
                                              data-placement="top"
                                              title="Teendő szerkesztése">
                                                            <button type="button"
                                                                    class="btn btn-edit-alert btn-sm btn-link btn-muted px-1 py-0"
                                                                    data-toggle="modal"
                                                                    data-target="#editAlertModal"
                                                                    data-alert-id="{{ $alert->id }}"
                                                                    data-message="{{ $alert->message }}"
                                                                    data-time="{{ $alert->time->format('Y/m/d H:i') }}">
                                                                <span class="icon icon-sm">
                                                                    <i class="fas fa-pen"></i>
                                                                </span>
                                                            </button>
                                                        </span>
                                        {{-- Törlés --}}
                                        <form action="{{ action('AlertsController@delete', $alert) }}"
                                              class="d-inline-block has-tooltip"
                                              method="POST" data-toggle="tooltip" data-placement="top"
                                              title="Teendő törlése">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-del-alert btn-sm btn-link btn-muted px-1 py-0">
                                                                <span class="icon icon-sm">
                                                                    <i class="fas fa-times"></i>
                                                                </span>
                                            </button>
                                        </form>
                                    </div> {{-- Módósítás, Törlés, Teljesítés vége --}}
                                </div>
                            @endforeach
                        </div>
                    @endif
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