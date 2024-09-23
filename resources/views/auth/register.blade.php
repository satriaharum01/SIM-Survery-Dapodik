@extends('template.app')

@section('content')


<div class="text-center mb-4">
    <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= asset('landing/login/assets/img/nav-logo.png') ?>" height="100" alt=""></a>
</div>
<form class="card card-md" action="<?= route('signup') ?>" method="POST" autocomplete="off" id="compose">
    @csrf
    <div class="card-body">
        <h2 class="card-title text-center mb-4">Register Pengguna</h2>
        <div class="mb-3">
            <label class="form-label">Nama Pengguna</label>
            <input type="text" name="nama" required class="form-control " value="{{ old('nama') }}" placeholder="Masukan Nama Pengguna" autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" required class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukan email" autocomplete="off">
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
                <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Password" required autocomplete="off">
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label">
                Nomor HP
            </label>
            <div class="input-group input-group-flat">
                <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Masukkan Nomor HP" required autocomplete="off">
            </div>
        </div>
		<div class="mb-3">
            <label class="form-label">Sekolah</label>
            <select name="sekolah" id="sekolah"  class="form-control theSelect">
				<option value="0" selected disabled>- Pilih Sekolah - </option>
			</select>
        </div>
        <div class="form-footer text-center">
            <button type="submit" class="btn btn-primary w-100 mb-2">Register</button>
            <a href="{{url('login')}}" >Sudah Punya Akun ? Klik Untuk Login </a>
        </div>
    </div>
</form>

@endsection
@section('js')
<script>
    $.ajax({
            url: "{{ url('/robot/sekolah/json')}}",
            type: "GET",
            cache: false,
            dataType: 'json',
            success: function(dataResult) {
                console.log(dataResult);
                var resultData = dataResult.data;
                $.each(resultData, function(index, row) {
                    $('#sekolah').append('<option value="' + row.id_sekolah + '">' + row.nama_sekolah + '</option>');
                })
            }
        });
        $(".theSelect").select2({
            dropdownParent: $('#compose')
        });
</script>
@endsection