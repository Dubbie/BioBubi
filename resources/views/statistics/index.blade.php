@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Fejléc szöveg --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Statisztika</h1>
        </div>

        {{-- Pikk-pakk stat--}}
        <div class="row">
            {{-- Megrendelők száma --}}
            <div class="col-md-3">
                <div class="card card-body shadow-sm border-0">
                    <div class="row">
                        <div class="col-md-auto d-flex text-center align-items-center">
                            <div class="bg-info-pastel text-info-pastel rounded-circle d-flex align-items-center justify-content-center"
                                 style="height: 48px; width: 48px;">
                                <span class="icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="font-weight-bold mb-0">{{ $counters['customers'] }}</h2>
                            <small class="text-muted font-weight-bold">Megrendelő</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Eladott termékek száma --}}
            <div class="col-md-3">
                <div class="card card-body shadow-sm border-0">
                    <div class="row">
                        <div class="col-md-auto d-flex text-center align-items-center">
                            <div class="bg-warning-pastel text-warning-pastel rounded-circle d-flex align-items-center justify-content-center"
                                 style="height: 48px; width: 48px;">
                                <span class="icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="font-weight-bold mb-0">{{ $counters['sold_items'] }}</h2>
                            <small class="text-muted font-weight-bold">Eladott termék</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Összes bevétel --}}
            <div class="col-md-3">
                <div class="card card-body shadow-sm border-0">
                    <div class="row">
                        <div class="col-md-auto d-flex text-center align-items-center">
                            <div class="bg-success-pastel text-success-pastel rounded-circle d-flex align-items-center justify-content-center"
                                 style="height: 48px; width: 48px;">
                                <span class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="font-weight-bold mb-0">{{ $counters['sold_total'] }} Ft</h2>
                            <small class="text-muted font-weight-bold">Bevétel</small>
                        </div>
                    </div>
                </div>

                <p class="text-center mt-2 mb-0">
                    <small class="text-small text-muted">Ha itt látok egy misit én is kérek belőle!</small>
                </p>
            </div>

            {{-- Termékek az adatbázisban --}}
            <div class="col-md-3">
                <div class="card card-body shadow-sm border-0">
                    <div class="row">
                        <div class="col-md-auto d-flex text-center align-items-center">
                            <div class="bg-danger-pastel text-danger-pastel rounded-circle d-flex align-items-center justify-content-center"
                                 style="height: 48px; width: 48px;">
                                <span class="icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="font-weight-bold mb-0">{{ $counters['items'] }}</h2>
                            <small class="text-muted font-weight-bold">Termék</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection