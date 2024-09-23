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
                        <th width="35%" style="text-align:center; vertical-align: middle;">Responden</th>
                        <th style="text-align:center; vertical-align: middle;">Jumlah Nilai Kenyataan</th>
                        <th style="text-align:center; vertical-align: middle;">Jumlah Nilai Harapan</th>
                    </tr>
                </thead>
                <tbody style="text-align:center; vertical-align: middle;">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="padding: 12px 6px;">Tabel GAP</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="data-gap" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                        <th style="text-align:center; vertical-align: middle;">Kode</th>
                        <th style="text-align:center; vertical-align: middle;">Performance (kenyataan)</th>
                        <th style="text-align:center; vertical-align: middle;">Importance (harapan)</th>
                        <th style="text-align:center; vertical-align: middle;">GAP</th>
                    </tr>
                </thead>
                <tbody style="text-align:center; vertical-align: middle;">
                    
                </tbody>
            </table>
            <div class="text-justify">
                <p>
                    Nilai GAP terkecil dengan kinerja yang perlu dipertahankan terletak pada pertanyaaan {{$atas}}
                </p>
                <p>
                    Nilai GAP terbesar dengan kinerja yang perlu ditingkatkan terletak pada pertanyaaan {{$bawah}}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="padding: 12px 6px;">Uji Validitas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="detail-data" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                        <th style="text-align:center; vertical-align: middle;">Section</th>
                        <th style="text-align:center; vertical-align: middle;">Pertanyaan</th>
                        <th style="text-align:center; vertical-align: middle;">R Hitung (kenyataan)</th>
                        <th style="text-align:center; vertical-align: middle;">R Hitung (harapan)</th>
                        <th style="text-align:center; vertical-align: middle;">R Tabel</th>
                        <th style="text-align:center; vertical-align: middle;">Hasil</th>
                    </tr>
                </thead>
                <tbody style="text-align:left; vertical-align: middle;">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="padding: 12px 6px;">Uji Reabilitas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="data-reliabel" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%" style="text-align:center; vertical-align: middle;">No</th>
                        <th style="text-align:center; vertical-align: middle;">Kuesioner</th>
                        <th style="text-align:center; vertical-align: middle;">Cronbach's Alpha</th>
                        <th style="text-align:center; vertical-align: middle;">Standar Cronbach's Alpha</th>
                        <th style="text-align:center; vertical-align: middle;">Keterangan</th>
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
            ]
        });
        table1 = $('#detail-data').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            info: false,
            lengthChange: false,
            paging: false,
            pageLength: 50,
            ajax: {
                url: '{{url("$page/detail")}}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'section'
                },
                {
                    data: 'pertanyaan', render:function(data)
                    {
                        return '<span class="text-left">'+data+'</span>';
                    }
                },
                {
                    data: 'nilai_r', render:function(data)
                    {
                        return data.toFixed(4);
                    }
                },
                {
                    data: 'nilai_q', render:function(data)
                    {
                        return data.toFixed(4);
                    }
                },
                {
                    data: 'r_table'
                },
                {
                    data: 'hasil'
                },
            ]
        });
        
        table2 = $('#data-gap').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            info: false,
            lengthChange: false,
            paging: false,
            pageLength: 50,
            ajax: {
                url: '{{url("$page/gap/0")}}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'section'
                },
                {
                    data: 'nilai_r', render:function(data)
                    {
                        if(data % 1 != 0){
                            return data.toFixed(2);
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: 'nilai_q', render:function(data)
                    {
                        if(data % 1 != 0){
                            return data.toFixed(2);
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: 'gap', render:function(data)
                    {
                        if(data % 1 != 0){
                            return data.toFixed(2);
                        }else{
                            return data;
                        }
                    }
                },
            ]
        });
        table3 = $('#data-reliabel').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            info: false,
            lengthChange: false,
            paging: false,
            pageLength: 50,
            ajax: {
                url: '{{url("$page/reliabel")}}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kuesioner'
                },
                {
                    data: 'crc', render:function(data)
                    {
                        if(data % 1 != 0){
                            return data.toFixed(4);
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: 'std', render:function(data)
                    {
                        if(data % 1 != 0){
                            return data.toFixed(2);
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: 'status'
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