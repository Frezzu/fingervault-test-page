@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Profile</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">Pair with FingerVault</div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="h6 text-muted">Page name:</div>
                            <p class="h5">{{env('APP_NAME')}}</p>
                        </div>

                        <div class="mb-3">
                            <div class="h6 text-muted">Host:</div>
                            <p class="h5">{{env('APP_HOST')}}</p>
                        </div>

                        <div class="mb-3">
                            <div class="h6 text-muted">Login:</div>
                            <p class="h5">{{$user->email}}</p>
                        </div>

                        <div class="mb-3">
                            <div class="h6 text-muted">User token:</div>
                            <p class="h5">{{$user->fingervault_user_token}}</p>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{route('fingervault.token.generate')}}" class="btn btn-primary mr-2">Generate User
                                Token</a>
                            <a href="{{$pairLink}}"
                               class="btn btn-success" {{empty($user->fingervault_user_token) ? "disabled" : ""}}>Pair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
