@extends('template.layout')

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
        <div class="table-responsive">
            <table class="table table-bordered" id="datawidth" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                        <th width="70%" style="text-align:center; vertical-align: middle;">Judul Survey</th>
                        <th style="text-align:center; vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="text-align:center; vertical-align: middle;">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- /.container-fluid -->
@endsection
@section('js')
<script>
    $(function() {
        table = $('#datawidth').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("admin/survey/json")}}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'judul'
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-primary btn-mulai" data-id="' + data + '"><span class="fa fa-play"></span> Mulai</button>'
                    }
                },
            ]
        });
    });

    /*Button Trigger
    $("body").on("click",".btn-mulai",function(){
        var id = jQuery(this).attr("data-id");
        Swal.fire({
                title: 'Pilih Opsi Survey ?',
                text: "Pilih nilai survey yang akan di input !",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Kenyataan',
                cancelButtonText: 'Harapan'
            }).then((result) => {
                if (result.value) {
                    window.location.href = "{{url('/robot/survey/mulai/')}}/"+ id +"/kenyataan" ;
                }else{
                    window.location.href = "{{url('/robot/survey/mulai/')}}/"+ id +"/harapan";
                }
            });
        
        
    });
    */
    $("body").on("click",".btn-mulai",function(){
        var id = jQuery(this).attr("data-id");
        window.location.href = "{{url('/robot/survey/mulai/')}}/"+ id +"/kenyataan" ;
    });
</script>
@endsection