@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="font-weight-bold">Megrendelő részletek</h1>
            <div class="btn-toolbar">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-link mr-2 text-decoration-none">Vissza</a>
                <a href="{{ action('CustomersController@create') }}" class="btn btn-sm btn-outline-secondary">Új
                    megrendelő</a>
            </div>
        </div>

        {{-- Részletek --}}
        <div class="row">
            <div class="col-12">

                <div class="row">
                    @if(!$customer->is_reseller)
                        <div class="col-md-6">
                            <small class="d-block font-weight-bold">Név</small>
                            <p id="customer-{{ $customer->id }}-name"
                               class="lead mb-2 copyable">{{ $customer->name }}</p>
                            <small class="d-block font-weight-bold">Telefonszám</small>
                            <p id="customer-{{ $customer->id }}-phone"
                               class="lead mb-2 copyable">{{ $customer->phone }}</p>
                            <small class="d-block font-weight-bold">Lakcím</small>
                            <p id="customer-{{ $customer->id }}-address"
                               class="lead mb-2 copyable">{{ $customer->address->getFormattedAddress() }}</p>
                            <small class="d-block font-weight-bold">E-mail</small>
                            <p id="customer-{{ $customer->id }}-email"
                               class="lead mb-0 copyable">{{ $customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            {{--@if(count($customer->orders) > 0)--}}
                            {{--<h5 class="font-weight-bold">Megvásárolt termékek:</h5>--}}
                            {{--@else--}}
                            {{--<p></p>--}}
                            {{--@endif--}}
                            <p class="lead font-weight-bold mb-0">Az ügyfél még nem vásárolt termékeket.</p>
                            <a href="#!" class="btn btn-sm btn-link text-decoration-none px-0">Adj hozzá vásárolt
                                termékeket.</a>
                        </div>
                    @else
                        <div class="col-md-6">
                            <p id="customer-{{ $customer->id }}-name"
                               class="lead mb-0 copyable">{{ $customer->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p id="customer-{{ $customer->id }}-phone"
                               class="lead mb-0 copyable">{{ $customer->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p id="customer-{{ $customer->id }}-address"
                               class="lead mb-0 copyable">{{ $customer->address->getFormattedAddress() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p id="customer-{{ $customer->id }}-email"
                               class="lead mb-0 copyable">{{ $customer->email }}</p>
                        </div>
                    @endif
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="font-weight-bold mb-0">Megjegyzések</p>
                        @if(count($customer->comments) > 0)
                            @foreach($customer->comments as $comment)
                                <div class="card card-body comment mb-4">
                                    <div class="comment-header d-flex justify-content-between align-items-start">
                                        <p class="font-weight-bold mb-0">{{ $comment->author->name }}</p>
                                        <small class="text-muted">{{ $comment->created_at->format('Y M d, H:i:s') }}</small>
                                    </div>
                                    <div class="comment-message">{{ $comment->message }}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="lead">A megrendelőhöz nem fűzött még hozzá senki semmit.</p>
                        @endif
                        <form action="{{ action('CommentsController@store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}" required>
                            <div class="form-group">
                                <label for="message" class="d-block d-md-none">Üzenet</label>
                                <textarea name="message" id="message" cols="30" rows="5" class="form-control"
                                          required></textarea>
                            </div>
                            <div class="form-group text-right mb-0">
                                <button class="btn btn-sm btn-success">Beküldés</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            function copyElementText(id) {
                const text = document.getElementById(id).innerText;
                const elem = document.createElement('textarea');
                document.body.appendChild(elem);
                elem.value = text;
                elem.select();
                document.execCommand('copy');
                document.body.removeChild(elem);

                console.log('Copied text.');
            }

            $('.copyable').on('click', (e) => {
                copyElementText(e.target.id);
            });
        });
    </script>
@endsection