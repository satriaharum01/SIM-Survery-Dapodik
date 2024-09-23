@extends('template.app')

@section('content')

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<div class="text-center mb-4">
    <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= asset('landing/login/assets/img/nav-logo.png') ?>" height="100" alt=""></a>
</div>
<form class="card card-md" action="<?= route('login') ?>" method="POST" autocomplete="off">
    @csrf
    <div class="card-body">
        <h2 class="card-title text-center mb-4">Masuk menggunakan Akunmu</h2>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukan email" autocomplete="off">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">
                Password
            </label>
            <div class="input-group input-group-flat">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="Password" autocomplete="off">
            </div>
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="form-footer text-center">
            <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            <a href="{{url('register')}}" >Belum Punya Akun ? Daftar disini !</a>
        </div>
    </div>
</form>

@endsection