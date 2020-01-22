@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start">
            <h1 class="font-weight-bold">Új megrendelő hozzáadása</h1>
            <div class="btn-toolbar">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-link text-decoration-none">Vissza</a>
            </div>
        </div>

        @include('inc.messages')

        {{-- Új megrendelő --}}
        <form action="{{ action('CustomersController@store') }}" method="POST">
            @csrf
            <div id="customer-basics">
                <div class="form-row my-4">
                    <div class="col-md-12">
                        <p class="lead">Először válassz megrendelő típust.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="form-group">
                            <div class="form-check customer-chooser">
                                <label for="customer-is-regular" class="customer-chooser-container">
                                    <input type="radio" id="customer-is-regular" name="is_reseller" class="form-check-input customer-chooser-input" value="false" @if(old('is_reseller') == 'false') checked @endif>
                                    <img src="https://img.icons8.com/dotty/150/000000/conference-call.png" data-is-reseller="false" class="customer-chooser-img">
                                    <span class="d-block h2 font-weight-bold customer-chooser-label">Ügyfél</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="form-group">
                            <div class="form-check customer-chooser">
                                <label for="customer-is-reseller" class="customer-chooser-container">
                                    <input type="radio" id="customer-is-reseller" name="is_reseller" class="form-check-input customer-chooser-input" value="true" @if(old('is_reseller') == 'true') checked @endif>
                                    <img src="https://img.icons8.com/dotty/150/000000/reseller.png" data-is-reseller="true" class="customer-chooser-img">
                                    <span class="d-block h2 font-weight-bold customer-chooser-label">Viszonteladó</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="customer-details" style="display: none;">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Megrendelő neve</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Megrendelő telefonszáma</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-lg-6">
                        <div class="form-row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="zip">Ir. Szám</label>
                                    <input type="number" id="zip" name="zip" class="form-control" value="{{ old('zip') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="city">Város</label>
                                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="street">Utca</label>
                                    <input type="text" id="street" name="street" class="form-control" value="{{ old('street') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-success">Hozzáadás</button>
            </div>
        </form>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            const details = document.getElementById('customer-details');

            function bindAllElements() {
                // Ügyfél típus választó
                $('.customer-chooser-input').on('change', (e) => {
                    handleResellerSwitch();
                });

                // Ir. szám kereső
                $('#city').on('keyup', (e) => {
                    const query = e.currentTarget.value;

                    if (query.length >= 3) {
                        searchByCity(query);
                    }
                });

                // Spinner
                $('form').on('submit', (e) => {
                    e.stopPropagation();
                    const submitBtn = $(e.currentTarget).find('button[type=submit]');
                    submitBtn.prop('disabled', true);
                    submitBtn.addClass('disabled');
                    submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                });
            }

            function searchByCity(query) {
                fetch('https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json?apiKey=C5r9d8VTg4gOFb56KZZIUg-LKcy4RwMRyeisBLh4F6c&query=' + query + '&country=HUN&beginHighlight=<b>&endHighlight=</b>')
                    .then((response) => response.json())
                    .then((data) => {
                        const postalCode = data['suggestions'][0]['address']['postalCode'];
                        if (postalCode) {
                            document.getElementById('zip').value = postalCode;
                        }
                    });
            }

            function handleResellerSwitch() {
                if ($('.customer-chooser-input:checked').length > 0) {
                    $(details).slideDown(250);
                }
            }

            function init() {
                bindAllElements();
                handleResellerSwitch();
            }

            init();
        });
    </script>
@endsection