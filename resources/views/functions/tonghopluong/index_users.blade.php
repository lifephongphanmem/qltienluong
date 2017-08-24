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

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Đơn vị sử dụng </label>
                            <div class="col-md-5">
                                {!! Form::select('madv',$model_donvi,$madv,array('id' => 'madv', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tháng</th>
                            <th class="text-center">Năm</th>
                            <th class="text-center">Nội dung bảng lương</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->thang}}</td>
                                    <td>{{$value->nam}}</td>
                                    <td>{{$value->noidung}}</td>
                                    <td>
                                        <a href="{{url('/chuc_nang/bang_luong/maso='.$value->mabl)}}" class="btn btn-warning btn-xs mbs">
                                            <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>
                                        <a href="{{url('/chuc_nang/bang_luong/in/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                            <i class="fa fa-print"></i>&nbsp; In bảng lương</a>
                                        <a href="{{url('/chuc_nang/bang_luong/in_bh/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                            <i class="fa fa-print"></i>&nbsp; In bảo hiểm</a>

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
                window.location.href = '/chuc_nang/tong_hop_luong/ma_so='+$('#madv').val()+'/don_vi';
            });
        })
    </script>
    @include('includes.modal.delete')
@stop