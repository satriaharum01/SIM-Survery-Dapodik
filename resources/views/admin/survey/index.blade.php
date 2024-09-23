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
        <button type="button" class="btn btn-primary btn-add" style="float: right;" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah Data 
        </button>
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

<!-- ============ MODAL DATA  =============== -->
<div class="modal fade" id="compose" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">    <div class="modal-dialog" role="document">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <center><b>
                <h4 class="modal-title" id="exampleModalLabel">Tambah Data</h4></b></center>    
            </div>
            <form action="<?=url('admin/survey/save');?>" method="post" id="compose-form">
                @csrf
                <div class="modal-body"> 
                    <div class="form-group">
                        <label>Judul Survey</label>
                        <input type="text" name="judul" class="form-control">
                    </div>
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-simpan">Simpan</button>   
                </div>
            </form>
        </div>
    </div>
</div>
<!--- END MODAL DATA --->

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
                        return '<button type="button" class="btn btn-success btn-edit" data-id="' + data + '"><span class="fa fa-edit"></span></button>\
                        <a class="btn btn-danger btn-hapus" data-id="' + data + '" data-handler="survey" href="<?= url('admin/survey/delete') ?>/' + data + '">\
                        <i class="fa fa-trash"></i></a> \
					    <form id="delete-form-' + data + '-survey" action="<?= url('admin/survey/delete') ?>/' + data + '" \
                        method="GET" style="display: none;"> \
                        </form>'
                    }
                },
            ]
        });
    });

    //Button Trigger
    $("body").on("click",".btn-add",function(){
        kosongkan();
        jQuery("#compose-form").attr("action",'<?=url($link);?>/save');
        jQuery("#compose .modal-title").html("Tambah <?=$title;?>");
        jQuery("#compose").modal("toggle");                    
    });

        $("body").on("click",".btn-edit",function(){
            var id = jQuery(this).attr("data-id");
                    
            $.ajax({
                    url: "<?=url('admin/survey');?>/find/"+id,
                    type: "GET",
                    cache: false,
                    dataType: 'json',
                    success: function (dataResult) { 
                        console.log(dataResult);
                        var resultData = dataResult.data;
                        $.each(resultData,function(index,row){
                            jQuery("input[name=judul]").val(row.judul);
                        })
                    }
                });
                jQuery("#compose-form").attr("action",'<?=url('survey');?>/update/'+id);
                jQuery("#compose .modal-title").html("Update <?=$title?>");
                jQuery("#compose").modal("toggle");
        });
        
        $("body").on("click",".btn-simpan",function(){
                Swal.fire(
                    'Data Dikirim!',
                    '',
                    'success'
                    )
        });

        function kosongkan()
        {
            jQuery("input[name=judul]").val("");
        }
</script>
@endsection