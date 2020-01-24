{{-- Lejárt határidó --}}
@if($alert->isOverdue())
    <span class="badge badge-danger-pastel badge-pill">
        {{-- Ikonka --}}
        <span class="icon icon-sm">
            <i class="fas fa-exclamation-triangle"></i>
        </span>
        {{-- Szöveg --}}
        <span>Lejárt a határidő</span>
    </span>
@elseif(!$alert->completed)
    <span class="badge badge-info-pastel badge-pill">
        {{-- Ikonka --}}
        <span class="icon icon-sm">
            <i class="fas fa-stopwatch"></i>
        </span>
        {{-- Szöveg --}}
        <span>{{ $alert->getRemainingTime() }}</span>
    </span>
@else
    <span class="badge badge-success-pastel badge-pill">
        {{-- Ikonka --}}
        <span class="icon icon-sm">
            <i class="fas fa-check"></i>
        </span>
        {{-- Szöveg --}}
        <span>Teljesítve</span>
    </span>
@endif