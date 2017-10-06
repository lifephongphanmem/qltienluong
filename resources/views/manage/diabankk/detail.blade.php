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
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
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
                        <b>DANH SÁCH CÁN BỘ THUỘC ĐỊA BÀN: {{$model_diaban->tendiaban}}</b>
                        <input type="hidden" id="madiaban" name="madiaban" value="{{$model_diaban->madiaban}}"/> <!-- Mã mặc định thêm cán bộ -->
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Họ tên cán bộ </label>
                            <div class="col-md-5">
                                <select class="form-control select2me" class="col-md-5" id="cbmacb" name="cbmacb">
                                    <option value="all" selected>-- Nhập họ tên cán bộ --</option>
                                    @foreach($model_cb as $cb)
                                        <option value="{{$cb->macanbo}}">{{$cb->tencanbo.' - Chức vụ: '.$cb->tencvcq}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="_btnaddPB" class="btn btn-success" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm cán bộ</button>
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên cán bộ</th>
                                <th class="text-center">Chức vụ</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td>{{$value->tencvcq}}</td>
                                        <td>{{$value->ghichu}}</td>
                                        <td>
                                            <button type="button" onclick="cfDel('{{$furl.'del_detail/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function add(){
            var valid=true;
            var message='';
            var macanbo = $('#cbmacb').val();
            var madiaban = $('#madiaban').val();
            if(macanbo=='all'){
                valid=false;
                message ='Bạn cần chọn cán bộ để thêm vào địa bàn.';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$furl}}' + 'add_canbo',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        macanbo: macanbo,
                        madiaban: madiaban
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 'success') {
                            location.reload();
                        }
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi lòi');
                    }
                });

            }else{
                toastr.error(message,'Lỗi!.');
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop