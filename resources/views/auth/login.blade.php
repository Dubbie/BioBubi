@extends('layouts.fullscreen')

@section('content')
    <div class="container mt-5 pt-5">
        <div class="row pt-5">
            <div class="col-md-4 offset-md-4">
                <div class="card card-body rounded-lg border-0 shadow">
                    <h5 class="font-weight-bold text-uppercase mb-4">Belépés</h5>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group ">
                            <label for="email" class="label-sm d-block d-md-none">E-Mail Cím</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="Email" required
                                   autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-1">
                            <label for="password" class="label-sm d-block d-md-none">Jelszó</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   placeholder="Password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember"><small>Bejelentkezve maradok</small></label>
                            </div>
                        </div>

                        <div class="form-group text-center mb-0">
                            <button type="submit" class="btn btn-sm btn-primary px-4">Belépés</button>
                        </div>
                    </form>
                </div>
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