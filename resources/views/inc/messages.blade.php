@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (isset($success))
    <div class="alert alert-success">
        <p class="mb-0">{{ $success }}</p>
    </div>
@endif