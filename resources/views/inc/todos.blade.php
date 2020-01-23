<div class="card card-body border-0 shadow-sm mb-4">
    <h5 class="font-weight-bold mb-4">Esedékes teendők</h5>
    @if(count($alerts) > 0)
        @foreach($alerts as $alert)
            <div class="action-hover-only d-flex justify-content-between @if(last($alerts) != $alert) mb-2 @endif">
                <div class="alert-details">
                    <p style="color: {{ $alert->user->color }};line-height: 1;" class="font-weight-bold mb-0">
                        {{ $alert->user->name }}
                        <span class="ml-2 font-weight-normal" data-toggle="tooltip"
                              data-placement="bottom"
                              title="{{ $alert->time->translatedFormat('Y F d, H:i') }}">{!! $alert->getStatusBadge() !!}</span>
                    </p>
                    <p class="mt-1 mb-0">
                        <a href="{{ action('CustomersController@show', $alert->customer) }}"
                           class="text-decoration-none text-muted">{{ $alert->message }}</a>
                    </p>
                </div>
                <div class="td-action alert-actions">
                    {{-- Teljesítés --}}
                    <form action="{{ action('AlertsController@complete', $alert) }}" class="d-inline-block"
                          method="POST" data-toggle="tooltip" data-placement="top" title="Teendő teljesítése">
                        @csrf
                        <button class="btn btn-complete-alert btn-sm btn-muted px-1 py-0">
                                    <span class="icon icon-sm">
                                        <i class="fas fa-check"></i>
                                    </span>
                        </button>
                    </form>
                    {{-- Módosítás --}}
                    <span data-toggle="tooltip" data-placement="top" title="Teendő szerkesztése">
                                <button type="button" class="btn btn-edit-alert btn-sm btn-muted px-1 py-0"
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
                    <form action="{{ action('AlertsController@delete', $alert) }}" class="d-inline-block"
                          method="POST" data-toggle="tooltip" data-placement="top" title="Teendő törlése">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-del-alert btn-sm btn-muted px-1 py-0">
                                    <span class="icon icon-sm">
                                        <i class="fas fa-times"></i>
                                    </span>
                        </button>
                    </form>
                </div> {{-- Módósítás, Törlés, Teljesítés vége --}}
            </div>
        @endforeach
    @else
        <div class="d-flex align-items-center">
            <img src="https://img.icons8.com/color/48/000000/checked--v1.png" class="d-inline-block mr-2"
                 style="width: 48px; height: 48px;">
            <p class="lead mb-0">Nincsenek esedékes teendők.</p>
        </div>
    @endif
</div>