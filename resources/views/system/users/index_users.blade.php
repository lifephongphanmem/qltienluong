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
                            @if(can('qltaikhoan','create'))
                                <a class="btn btn-default" href="{{url($url.'ma_so='.$madv.'/create')}}">
                                    <i class="fa fa-plus"></i> Thêm mới
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Đơn vị sử dụng </label>
                            <div class="col-md-5">
                                {!! Form::select('madv',$model_donvi,$madv,array('id' => 'madv', 'class' => 'form-control select2me'))!!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 4%">STT</th>
                                <th class="text-center">Tên người sử dụng</th>
                                <th class="text-center">Tài khoản</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($model))
                                    @foreach($model as $key=>$value)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>{{$value->name}}</td>
                                            <td>{{$value->username}}</td>
                                            <td>{{$value->status}}</td>
                                            <td>
                                                @if(can('qltaikhoan','edit'))
                                                    <a class="btn btn-default btn-xs mbs" href="{{url($url.'list_user?&username='.$value->username)}}">
                                                        <i class="fa fa-edit"></i> Chỉnh sửa
                                                    </a>
                                                @endif
                                                <!--a class="btn btn-default btn-xs mbs" href="{{url($url.'ma_so='.$value->username.'/permission')}}">
                                                    <i class="fa fa-list-ul"></i> Phân quyền
                                                </a-->
                                                @if(can('qltaikhoan','delete'))
                                                    <button type="button" onclick="cfDel('{{$url.'del_taikhoan/'.$value->username}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                                @endif
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

        $(function(){
            $('#madv').change(function() {
                window.location.href = '/danh_muc/tai_khoan/list_user?&madv='+$('#madv').val();
            });
        })
    </script>
    @include('includes.modal.delete')
@stop