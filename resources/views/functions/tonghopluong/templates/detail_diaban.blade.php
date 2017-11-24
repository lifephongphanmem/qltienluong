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
                        <b>DỮ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG THÁNG {{$model_thongtin->thang}} NĂM {{$model_thongtin->nam}} THEO ĐỊA BÀN QUẢN LÝ</b>
                        <input type="hidden" id="mathdv" name="mathdv" value="{{$model_thongtin->mathdv}}"/> <!-- Mã mặc định thêm cán bộ -->
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên địa bàn</th>
                                <th class="text-center">Phân loại</br>địa bàn</th>
                                <th class="text-center">Lương ngạch</br>bậc</th>
                                <th class="text-center">Phụ cấp</br>lương</th>
                                <th class="text-center">Các khoản</br>phụ cấp</th>
                                <th class="text-center">Tổng tiền</br>lương và phụ</br> cấp</th>
                                <th class="text-center">Tổng khoản</br>nộp theo</br>lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tendiaban}}</td>
                                        <td>{{$value->phanloai}}</td>
                                        <td class="text-right">{{dinhdangso($value->heso)}}</td>
                                        <td class="text-right">{{dinhdangso($value->hesopc)}}</td>
                                        <td class="text-right">{{dinhdangso($value->tonghs - $value->heso - $value->hesopc)}}</td>
                                        <td class="text-right">{{dinhdangso($value->tonghs)}}</td>
                                        <td class="text-right">{{dinhdangso($value->tongbh)}}</td>
                                        <td>
                                            <a href="{{url($furl.'edit_detail_diaban?mathdv='.$value->mathdv.'&madiaban='.$value->madiaban)}}" class="btn btn-info btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</a>
                                            <button type="button" onclick="cfDel('{{$furl.'del_detail/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-offset-5 col-md-2">
                        <a href="{{url($furl.'index?nam='.date('Y'))}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
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