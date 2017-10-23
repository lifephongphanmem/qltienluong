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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ</div>
                    <div class="actions">
                        <div class="actions">
                            <a class="btn btn-default" href="{{url($url.'ma_so='.$madvbc.'/create')}}">
                                <i class="fa fa-plus"></i> Thêm mới đơn vị
                            </a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 4%">STT</th>
                                <th class="text-center">Mã đơn vị</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Đơn vị quản lý</th>
                                <th class="text-center">Phân loại đơn vị</th>
                                <th class="text-center">Cấp dự toán</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($model))
                                    @foreach($model as $key=>$value)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>{{$value->madv}}</td>
                                            <td>{{$value->tendv}}</td>
                                            <td>{{$value->tencqcq}}</td>
                                            <td>{{$value->capdutoan}}</td>
                                            <td>{{$value->diachi}}</td>
                                            <td>
                                                <a class="btn btn-default btn-xs mbs" href="{{url($url.'ma_so='.$value->madvbc.'&don_vi='.$value->madv.'/edit')}}">
                                                    <i class="fa fa-edit"></i> Chỉnh sửa
                                                </a>

                                                <button type="button" onclick="cfDel('{{$url.'del_donvi/'.$value->madv}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    @include('includes.modal.delete')
@stop