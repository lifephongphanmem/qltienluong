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
                        <b>DỮ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG THÁNG {{$model_thongtin->thang}} NĂM {{$model_thongtin->nam}}</b>
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
                                <th class="text-center">Nguồn kinh phí</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Hệ số</br>lương</th>
                                <th class="text-center">Hệ số</br>vượt khung</th>
                                <th class="text-center">Tổng hệ</br>số phụ cấp</th>
                                <th class="text-center">Tổng tiền</br>lương</th>
                                <th class="text-center">Tổng khoản nộp</br>theo lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tennguonkp}}</td>
                                        <td>{{$value->tencongtac}}</td>
                                        <td>{{$value->heso}}</td>
                                        <td>{{$value->vuotkhung}}</td>
                                        <td>{{$value->tonghs}}</td>
                                        <td>{{number_format($value->tongtl)}}</td>
                                        <td>{{number_format($value->tongbh)}}</td>
                                        <td>
                                            <a href="{{url($furl.'edit_detail?mathdv='.$value->mathdv.'&manguonkp='.$value->manguonkp.'&macongtac='.$value->macongtac)}}" class="btn btn-info btn-xs mbs">
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
                        <a href="{{url('/chuc_nang/tong_hop_luong/don_vi/index?nam='.date('Y'))}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes.modal.delete')
@stop