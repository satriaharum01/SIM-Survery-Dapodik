<?php
$tgla = date('Y-m-d');
$bulan = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
);

$array1 = explode("-", $tgla);
$tahun = $array1[0];
$bulan1 = $array1[1];
$hari = $array1[2];
$bl1 = $bulan[$bulan1];
$tgl1 = $hari . ' ' . $bl1 . ' ' . $tahun;


?>

<!DOCTYPE html>
<html>
	<head>
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome-free-6/css/all.css') }}">
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <!-- SweetAlert 2 -->
    <script src="{{ asset('assets/dist/sweetalert2/sweetalert2.all.min.js') }}">
    </script>
    <link rel="{{ asset('assets/dist/sweetalert2/sweetalert2.min.css') }}">
    <title> {{env('APP_NAME')}} - Survey {{$load->judul}}</title>
	<style>
        .border-soft{
            border: 1.2px solid lightgrey;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .login-form-title img {
        width: 15%;
        margin-top: auto;
        margin-bottom: auto;
        }
        .print-content{
        margin-top: auto;
        margin-bottom: auto;
        margin-left:2rem;
        }
        .login-form-title {
        margin-left: 1%;
        margin-right: 5%;
        }
        .paraf-bot{
            margin-right:4rem;
            line-height: 1.5;
            text-align:justify;
            padding-bottom:5rem;
        }
        th{
            text-align:center;
        }
        .hr1{
            margin-left:auto;
            margin-top:1%;
            margin-bottom:0;
            margin-right:auto;
            border: 2px solid black;
            width: 99%;
        }
        .hr2{
            margin-left:auto;
            margin-top:5px;
            margin-right:auto;
            border: 1px solid black;
            width: 99.3%;
        }
		body {
			background: rgba(0,0,0,0.2);
            zoom:1.5;
		}
        .tanda-tangan{
            float: right;
            margin-top: 50px;
            position: relative;
            right: 10px;
        }
		page[size="A4"] {
			background: white;
			
            width: 80%;
			display: block;
			margin: 0 auto;
            margin-bottom: 2.54cm;
			box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
			padding-left:1cm;
			padding-right:1cm;
			padding-top:0.5cm;
			padding-bottom:1.5cm;
		}
        .wrap-top{
            margin-top:9%;
        }
        .container{
            margin-bottom:2rem;
        }
		@media print {
	         .container{
                display:none;
            }
		}
	</style>
	</head>
	<body>
        <header class="navbar fixed-top navbar-expand-lg navbar-light bg-light container mx-auto d-flex justify-content-between">
            <h3 class="pull-left">
            {{env('APP_NAME')}} - Survey {{$load->judul}}
            </h3>
            @if($opsii == 'harapan')
            <button type="button" class="btn btn-success btn-md" onclick="selesai()">
                <i class="fa fa-check"> </i> Selesai
            </button>
            @else
            <button type="button" class="btn btn-primary btn-md" onclick="lanjut()">
                <i class="fa fa-arrow-right"> </i> Next
            </button>
            @endif
        </header>
        <div id="printableArea" class="container-fluid wrap-top">
            <page size="A4">
                <section class="content" style="color:black;">
                    <span class="login-form-title">
                        <center>
                            <div style="display: flex;flex-direction: row;justify-content: center; height:50%;">
                                <img src="{{asset('landing/login/img/logo-dikdasmen.png')}}" class="pr-2" alt="logo">
                                <div class="print-content text-left">
                                    <h1 style="margin-bottom:0px;">
                                        Kuesioner Analasis Kualitas Layanan Dapodik
                                    </h1>
                                    <h3>{{$sub_judul}}</h3>
                                </div>
                            </div>
                            </center>
                    </span>
                    <hr class="hr2">
                    <article class="mx-3">
                        Kepada Yth. Saudara/i <br>
                        Operator Dapodik<br>
                        Di Deli Serdang <br> <br>
                        <p>
                        <div class="text-justify" style="text-justify: inter-word;">
                        Assalamu'alaikum wr.wb., Dalam rangka menyelesaikan tugas akhir di Universitas Islam Negeri Sumatera Utara (UINSU) Medan, maka saya ingin mengadakan penelitian mengenai Kualitas Layanan Sistem Informasi Dapodik dengan judul "Analisis Layanan Sisfo Dapodik Di Dinas Pendidikan Menggunakan Metode E-servqual".  <br>
                        Sehubungan dengan itu, saya membutuhkan sejumlah data untuk diolah dan kemudian akan dijadikan sebagai bahan penelitian melalui kerjasama dan kesediaan saudara dalam mengisi kuisioner ini. Kami harapkan saudara/i mengisi kuesioner ini dengan sungguh-sungguh agar didapatkan data yang valid.  <br>
                        Atas perhatian dan kesediaan saudara/i sekalian mengisi kuesioner ini, saya mengucapkan banyak terima kasih. <br> Wassalamu'alaikum wr. wb.
                         <p class="mt-3">Hormat saya,    <br>
                        <label>Fitri Rizky Taravita</label> 
                        </p>
                        </div></p>
                    </article>
                    <hr class="hr2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-left mx-2 border-soft">
                                <h3 style="margin-bottom:1rem;">Data Responden</h3>
                                <div class="form-group">
                                    <label>Nama Responden</label>
                                    <input type="text" name="nama" class="form-control" value="{{$user->name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama Satuan Pendidikan</label>
                                    <input type="text" name="sekolah" class="form-control" value="{{$user->cari_sekolah->nama_sekolah}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{$user->email}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="no_hp" class="form-control" @if($user->no_hp == '0') value="Tidak Ada Nomor HP" @else value="0{{$user->no_hp}}" @endif readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="hr2">
                    <div class="row"> 
                        <div class="col-md-12 mb-2">
                            <div class="text-left mx-2 border-soft">
                                <h3 class="text-muted">Panduan</h3>
                                <div class="text-justify" style="text-justify: inter-word;">
                                    <p>
                                    Isilah Jawaban Saudara/i dengan cara memilih salah satu opsi yang telah tersedia. Setiap pertanyaan diikuti oleh 5 pilihan dengan ketentuan sebagai berikut: <br> </p>
                                    <label>STS : Sangat Tidak Setuju</label> <br>
                                    <label>TS : Tidak Setuju</label> <br>
                                    <label>CS : Cukup Setuju</label> <br>
                                    <label>S : Setuju</label> <br>
                                    <label>SS : Sangat Setuju</label> <br>
                                </div>
                            </div>
                        </div>
                        <?php $no = 1?>
                        @foreach($result as $row)
                        <div class="col-md-12 mb-2">
                            <div class="text-left mx-2 border-soft">
                                <h4 class="text-info">{{$no++}}. {{$row->keterangan}}</h4>
                                <div class="text-justify" style="text-justify: inter-word;">
                                    <p>
                                    Pilih Jawaban Anda : <br> </p>
                                    @foreach($pilihan as $opt => $key)
                                    <div class="d-flex align-items-center mb-3">
                                        
                                        @if($row->jawaban == $opt)
                                            <input type="radio" class="mt-0 mr-2 btn-cek" data-id="{{$row->id}}" name="val[{{$row->id}}]" value="{{$opt}}" checked>
                                        @else
                                            <input type="radio" class="mt-0 mr-2 btn-cek" data-id="{{$row->id}}" name="val[{{$row->id}}]" value="{{$opt}}">
                                        @endif
                                        <label class="mb-0">{{$key}}</label><br>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </page>
            
        </div>
        <!-- ============ MODAL DATA JADWAL =============== -->

    <div class="modal" id="compose" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <center><b>
                    <h4 class="modal-title" id="exampleModalLabel">Hasil Survey</h4></b></center>    
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="datawidth" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                                    <th width="65%" style="text-align:center; vertical-align: middle;">Pertanyaan</th>
                                    <th style="text-align:center; vertical-align: middle;">Jawaban</th>
                                    <th style="text-align:center; vertical-align: middle;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">

                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-danger btn-tutup" onclick="nextpage()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<!--- END MODAL DATA JADWAL--->
  </body>
  

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Page level plugins -->
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
var id = '{{$load->id}}';
$(function() {
    //console.log(url);
    table = $('#datawidth').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        info: false,
        lengthchange:false,
        paging:false,
        ajax: {
            url: '{{url("robot/survey/hasil/$opsii")}}'
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'keterangan'
            },
            {
                data: 'jawaban'
            },
            {
                data: 'nilai'
            },
        ]
    });
})
$("body").on("click",".btn-cek", function(){
    var nilai = jQuery(this).val();
    var name = jQuery(this).attr("name");
    var od = jQuery(this).attr("data-id");
    var url = "{{ url('/robot/survey/update/') }}/"+od;
    //alert(name);
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            Nilai: nilai,
            Name: name,
            Opsi: '{{$p_opt}}',
        },
        success: function(response) {
            if (response.success) {
                console.log(response);
            } else {
                alert("Error")
            }
        },
        error: function(error) {
            console.log(error)
        }
    });
})

function printDiv(divName) {
    //var printContents = document.getElementById(divName).innerHTML;
    //var originalContents = document.body.innerHTML;
    //document.body.innerHTML = originalContents;
    window.print();
}

function selesai()
{
    Swal.fire({
        title: 'Akhiri Survey ?',
        text: "Silahkan cek kembali pilihan anda !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            feedback();
            //window.location.href = "{{route('operator.survey')}}";
        }
    });
}
function lanjut()
{
    var id = '{{$load->id}}';
    Swal.fire({
        title: 'Lanjutkan ke Survey Berikutnya ?',
        text: "Silahkan cek kembali pilihan anda !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            feedback();
            //window.location.href = "{{url('/robot/survey/mulai/')}}/"+ id +"/harapan";
        }
    });
}

function feedback()
{
    table.ajax.url('{{url("/robot/survey/hasil/$opsii")}}').load();
    
    jQuery("#compose").modal("toggle");
}

function nextpage()
{
    var ccc = '{{$opsii}}';
    if(ccc === 'harapan')
    {
        window.location.href = "{{route('operator.survey')}}";
    }else{
        window.location.href = "{{url('/robot/survey/mulai/')}}/"+ id +"/harapan";
    }
}
</script>
</html>