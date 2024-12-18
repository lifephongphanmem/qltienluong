<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
?>
@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        THÔNG TIN THIẾT LẬP ĐƠN VỊ
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                <?php $i=1; ?>
                                @foreach ($model as $k=>$ct)
                                <?php $m_capduoi=$model_capduoi->where('macqcq',$ct->madv);
                                    $j=1;
                                     ?>
                                <tr>

                                    <td class="text-center">{{convert2Roman($i++)}}</td>
                                    <td>{{$ct->tendv}}</td>
                                    <td>
                                        <button type="button"
                                        onclick="editPB('{{ $ct->stt }}','{{ ++$maxstt_parent }}','{{ $ct->madv }}')"
                                        class="btn btn-default btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                    </td>

                                </tr>
                                @foreach ($m_capduoi as $key=>$val)
                                <tr>
                                    <td class="text-right">{{$j++}}</td>
                                    <td>{{$val->tendv}}</td>
                                    <td>
                                        {{-- <button type="button"
                                        onclick="editPB('{{ $val->stt }}','{{ ++$maxstt_children }}','{{ $val->madv }}')"
                                        class="btn btn-default btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp; Sửa</button> --}}
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin đơn vị -->
    {!! Form::open(['url' => '/he_thong/bao_cao/sua', 'id' => 'frm_them', 'method' => 'POST']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi tiết</h4>
                </div>
                <div class="modal-body">
                   
                    <label class="form-control-label">Số thứ tự</label>
                    {!! Form::text('stt_parent', null, ['class' => 'form-control']) !!}

                    <div class="row" style="margin-top:10px">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="form-control-label">Đơn vị cấp dưới</label>
                                {{-- {!! Form::text('maphanloai_nhom', null, ['class' => 'form-control']) !!} --}}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label">Số thứ tự</label>
                                {{-- <button type="button" class="btn btn-default" data-target="#maphanloai-modal"
                                    data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;</button> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row" id="dv_capduoi">
                    </div>

                </div>
                <input type="hidden" name="madv" id="madv">
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <script>
        function editPB(stt, maxstt,madv) {
            if(stt ==''){
                stt=maxstt;
            }
            // console.log(maxstt);
            $('#frm_them').find("[name^='stt']").val(stt);
            $('#frm_them').find("[name^='madv']").val(madv);
            // $('#frm_them').find("[name^='capdo_nhom']").val(capdo_nhom).trigger('change');
            // $('#frm_them').find("[name^='maphanloai_goc']").val(maphanloai_goc).trigger('change');
            // $('#frm_them').find("[name^='chitiet']").val(chitiet).trigger('change');
            // $('#frm_them').find("[name^='sapxep']").val(sapxep);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/he_thong/bao_cao/getDV',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        macqcq: madv
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            $('#dv_capduoi').replaceWith(data.html);
                        }
                    },
                    error: function(message) {
                        console.log(message);
                    }
                });
            $('#create-modal').modal('show');
        }
    </script>
@stop
