@extends('layouts.fullscreen')

@section('content')
    <div class="container mt-5 pt-5">
        <div class="row pt-5">
            <div class="col-md-4 offset-md-4 text-center">
                <div class="card card-body rounded-lg border-0 shadow text-left">
                    <h5 class="font-weight-bold mb-4 text-uppercase">Regisztráció</h5>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group mb-1">
                            <label for="name" class="label-sm">Név</label>
                            <input id="name" type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-1">
                            <label for="email" class="label-sm">E-mail Cím</label>
                            <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-1">
                            <label for="password" class="label-sm">Jelszó</label>
                            <input id="password" type="password"
                                   class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm" class="label-sm">Jelszó megerősítése</label>
                            <input id="password-confirm" type="password" class="form-control form-control-sm"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group text-center mb-0">
                            <button type="submit" class="btn btn-sm btn-primary px-4">Regisztrálok</button>
                        </div>
                    </form>
                </div>
                <a href="{{ route('login') }}" class="btn btn-sm btn-link text-white-50 text-decoration-none mt-2">Belépés</a>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $('form').on('submit', (e) => {
           e.stopPropagation();
           const submitBtn = $(e.currentTarget).find('button[type=submit]');
           submitBtn.prop('disabled', true);
           submitBtn.addClass('disabled');
           submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        });
    </script>
@endsection
