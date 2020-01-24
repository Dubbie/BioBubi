<div class="col-12">
    <div class="card card-body border-0 shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <small class="d-block font-weight-bold">Név</small>
                <p id="customer-{{ $customer->id }}-name"
                   class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                   title="Név kimásolva">{{ $customer->name }}</p>
            </div>
            <div class="col-md-6">
                <small class="d-block font-weight-bold">Telefonszám</small>
                <p id="customer-{{ $customer->id }}-phone"
                   class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                   title="Telefonszám kimásolva">{{ $customer->phone }}</p>
            </div>
            <div class="col-md-6">
                <small class="d-block font-weight-bold">Lakcím</small>
                <p id="customer-{{ $customer->id }}-address"
                   class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                   title="Lakcím kimásolva">{{ $customer->address->getFormattedAddress() }}</p>
            </div>
            <div class="col-md-6">
                <small class="d-block font-weight-bold">E-mail</small>
                <p id="customer-{{ $customer->id }}-email"
                   class="lead d-inline-block mb-0 copyable" data-toggle="tooltip" data-placement="right"
                   title="E-mail kimásolva">{{ $customer->email }}</p>
            </div>
        </div>
    </div>

    {{-- Megjegyzések --}}
    @include('inc.comments')
</div>