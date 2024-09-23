@extends('template.layout');
@section('content')



<!-- Page Heading
<h1 class="h3 mb-2 text-gray-800">Tables</h1>
<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
    For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.</p>
 -->
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="padding: 12px 6px;">{{$title}}</h6>
    </div>
    <div class="card-body">
        <p>Berikut adalah informasi mengenai sekolah anda. Untuk memperbarui sekolah anda silahkan ubah kemudian tekan simpan</a></p>
        <form action="<?=url($page);?>/update/{{$load->id_sekolah}}" method="post" id="compose-form" class="row">
            @csrf
            <div class="modal-body col-lg-12"> 
                <div class="form-group">
                    <label>Nama Sekolah</label>
                    <input type="text" readonly name="nama_sekolah" value="{{$load->nama_sekolah}}" class="form-control" placeholder="KB Cahaya" required>
                </div>
                <div class="form-group">
                    <label>NPSN</label>
                    <input type="number" name="npsn" value="{{$load->npsn}}" readonly class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea  name="alamat" class="form-control" value="" placeholder="Contoh: Jl. Asia Megamas No.5" rows="2">{{$load->alamat}}</textarea>
                </div>
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="nama" value="{{$profil->name}}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email Pengguna</label>
                    <input type="email" name="email" value="{{$profil->email}}" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-simpan">Simpan</button>   
            </div>
        </form>
    </div>
</div>

<!-- /.container-fluid -->
@endSection()
@section('js')
<script>
    $("body").on("click",".btn-simpan",function(){
        Swal.fire(
            'Data Disimpan!',
            '',
            'success'
            )
    });
        
    function kosongkan()
    {
        jQuery("input[name=nama]").val("");
        jQuery("input[name=harga]").val("");
    }
</script>
@endSection