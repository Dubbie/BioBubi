<div class="row">
    {{-- Alap adatok --}}
    <div class="col-md-7">
        <div class="card card-body border-0 shadow-sm">
            <small class="d-block font-weight-bold">Név</small>
            <p id="customer-{{ $customer->id }}-name"
               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
               title="Név kimásolva">{{ $customer->name }}</p>
            <small class="d-block font-weight-bold">Telefonszám</small>
            <p id="customer-{{ $customer->id }}-phone"
               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
               title="Telefonszám kimásolva">{{ $customer->phone }}</p>
            <small class="d-block font-weight-bold">Lakcím</small>
            <p id="customer-{{ $customer->id }}-address"
               class="d-inline-block lead mb-2 copyable" data-toggle="tooltip" data-placement="right"
               title="Lakcím kimásolva">{{ $customer->address->getFormattedAddress() }}</p>
            <small class="d-block font-weight-bold">E-mail</small>
            <p id="customer-{{ $customer->id }}-email"
               class="d-inline-block lead mb-0 copyable" data-toggle="tooltip" data-placement="right"
               title="E-mail kimásolva">{{ $customer->email }}</p>
        </div>

        {{-- Megjegyzések --}}
        @include('inc.comments')
    </div>

    {{-- Vásárolt termékek --}}
    <div class="col-md-5">
        @if(count($customer->purchases) > 0)
            <div class="card card-body border-0 shadow-sm p-0">
                <h5 class="font-weight-bold mb-0 p-3">Megvásárolt termékek:</h5>
                @foreach($customer->purchases as $purchase)
                    <div class="customer-purchase d-flex justify-content-between action-hover-only px-3">

                        {{-- Termék adatai --}}
                        <span class="mb-0 item-name">
                            <span>{{ $purchase->quantity }} × {{ $purchase->getItemName() }}</span>
                            <small class="d-block text-muted text-small">
                                @if($purchase->date)
                                    {{ $purchase->date->translatedFormat('Y F d, H:i') }}
                                @else
                                    -
                                @endif
                            </small>
                        </span>

                        <div class="purchase-details">
                            {{-- Státusz --}}
                            @if($purchase->completed)
                                <span class="ml-2 badge badge-success-pastel badge-pill">
                                    Teljesítve
                                </span>
                            @endif

                            {{-- Ár --}}
                            <span class="h5 mb-0 item-price">{{ number_format($purchase->price * $purchase->quantity, 0, '.', ' ') }}
                                <small class="font-weight-bold">Ft</small>
                            </span>

                            {{-- Gombok --}}
                            <div class="td-action text-right">
                                {{--  Teljesítés --}}
                                @if(!$purchase->completed)
                                    <span data-toggle="tooltip"
                                          data-placement="top"
                                          title="Rögzített vásárlás teljesítése">
                                        <button class="btn btn-complete-purchase btn-sm btn-muted"
                                                data-toggle="modal"
                                                data-target="#completePurchaseModal"
                                                data-purchase-id="{{ $purchase->id }}"
                                                data-purchase-date="{{ $purchase->date ? $purchase->date->format('Y/m/d H:i') : '' }}">
                                            <span class="icon icon-sm">
                                                <i class="fas fa-pen"></i>
                                            </span>
                                        </button>
                                    </span>
                                @endif

                                {{-- Törlés --}}
                                <form class="d-inline-flex has-tooltip"
                                      action="{{ action('CustomerItemsController@delete', $purchase) }}"
                                      method="POST"
                                      data-toggle="tooltip" data-placement="top"
                                      title="Rögzített vásárlás törlése">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-del-purchase btn btn-muted btn-sm p-1">
                                        <span class="icon icon-sm">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Összegző --}}
                <div class="d-flex justify-content-between align-items-baseline border-top px-3 py-3">
                    <h4 class="font-weight-bold">Összesen: </h4>
                    <h4 class="font-weight-bold">{{ number_format($total, 0, '.', ' ') }}
                        <small class="font-weight-bold">Ft</small>
                    </h4>
                </div>

                {{-- További vásárlások rögzítése --}}
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-sm btn-teal shadow" data-toggle="modal"
                            data-target="#newCustomerItemsModal">További vásárlások rögzítése
                    </button>
                </div>
            </div>
        @else
            <p class="lead font-weight-bold mb-2">Az ügyfél még nem vásárolt termékeket.</p>
            <button type="button" class="btn btn-sm btn-primary shadow" data-toggle="modal"
                    data-target="#newCustomerItemsModal">Vásárlások rögzítése
            </button>
        @endif
    </div>
</div>