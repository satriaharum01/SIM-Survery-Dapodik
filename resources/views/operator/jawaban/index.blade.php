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
        <div class="table-responsive">
            <table class="table table-bordered" id="datawidth" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                        <th width="35%" style="text-align:center; vertical-align: middle;">Responden</th>
                        <th width="15%" style="text-align:center; vertical-align: middle;">Jumlah Nilai Kenyataan</th>
                        <th width="15%" style="text-align:center; vertical-align: middle;">Jumlah Nilai Harapan</th>
                        <th width="15%" style="text-align:center; vertical-align: middle;">Gap (selisih)</th>
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
@endSection()
@section('js')
<script>
    $(function() {
        $.ajax({
            url: "{{ url('/admin/section/json')}}",
            type: "GET",
            cache: false,
            dataType: 'json',
            success: function(dataResult) {
                console.log(dataResult);
                var resultData = dataResult.data;
                $.each(resultData, function(index, row) {
                    $('#section').append('<option value="' + row.id + '">' + row.for_filter + '</option>');
                })
            }
        });

        table = $('#datawidth').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("$page/json")}}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'responden'
                },
                {
                    data: 'n_kenyataan'
                },
                {
                    data: 'n_harapan'
                },
                {
                    data: 'gap'
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-primary btn-eye" data-id="' + data + '"><i class="fa fa-eye"></i> Detail</button>'
                    }
                },
            ]
        });
    });

    //Button Trigger
    $("body").on("click",".btn-add",function(){
            kosongkan();
            jQuery("#compose-form").attr("action",'<?=url($page);?>/save');
            jQuery("#compose .modal-title").html("Tambah <?=$title;?>");
            jQuery("#compose").modal("toggle");           
    });

    $("body").on("click",".btn-edit",function(){
        var id = jQuery(this).attr("data-id");
                    
        $.ajax({
            url: "<?=url($page);?>/find/"+id,
            type: "GET",
            cache: false,
            dataType: 'json',
            success: function (dataResult) { 
                console.log(dataResult);
                var resultData = dataResult.data;
                $.each(resultData,function(index,row){
                    jQuery("#compose-form input[name=keterangan]").val(row.keterangan);
                    jQuery("#compose-form select[name=section]").val(row.id_section);
                })
            }
        });
        jQuery("#compose-form").attr("action",'<?=url($page);?>/update/'+id);
        jQuery("#compose .modal-title").html("Update <?=$title?>");
        jQuery("#compose").modal("toggle");
    });
    
    $("body").on("click",".btn-simpan",function(){
        Swal.fire(
            'Data Disimpan!',
            '',
            'success'
            )
    });
        
    function kosongkan()
    {
        jQuery("input[name=keterangan]").val("");
        jQuery("select[name=section]").val("0");
    }
</script>
@endSection