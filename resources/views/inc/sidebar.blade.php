<div class="card card-body border-0 shadow-sm">
    <form id="form-filter" action="{{ action('CustomersController@index') }}" method="GET">
        <p class="mb-0">
            <small class="font-weight-bold">Szűrők</small>
        </p>

        <div class="form-group">
            <label for="filter-name">Név tartalmzza</label>
            <input type="text" id="filter-name" name="filter-name"
                   class="form-control form-control-sm" value="{{ $filter['name'] ?? '' }}">
        </div>

        <p class="mb-0">Város</p>
        <div class="filter overflow-auto mb-4">
            @foreach($cities as $city)
                <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input filter-city"
                               name="filter-city[]"
                               id="filter-city-{{ \Illuminate\Support\Str::slug($city['city']) }}"
                               value="{{ $city['city'] }}"
                               @if(isset($city['checked'])) checked @endif>
                        <label class="custom-control-label"
                               for="filter-city-{{ \Illuminate\Support\Str::slug($city['city']) }}">{{ $city['city'] }}
                            <span class="text-muted">{{ $city['total'] }}</span></label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-group mb-0 d-flex justify-content-between">
            <button type="button" id="btn-reset-filter-form"
                    class="btn btn-sm btn-link text-secondary px-0">Visszaállít
            </button>
            <button type="submit" class="btn btn-sm btn-primary">Szűrés</button>
        </div>
    </form>
</div>