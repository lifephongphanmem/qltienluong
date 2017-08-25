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
                            <label class="control-label col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select(
                                'thang',
                                array(
                                '01' => '01',
                                '02' => '02',
                                '03' => '03',
                                '04' => '04',
                                '05' => '05',
                                '06' => '06',
                                '07' => '07',
                                '08' => '08',
                                '09' => '09',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                                ),null,
                                array('id' => 'thang', 'class' => 'form-control'))
                                !!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select(
                                'nam',
                                array(
                                '2015' => '2015',
                                '2016' => '2016',
                                '2017' => '2017'
                                ),null,
                                array('id' => 'nam', 'class' => 'form-control'))
                                !!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Khu vực </label>
                            <div class="col-md-4">
                                {!!Form::select('level', array('KVHCSN'=>'Khu vực HCSN, Đảng, Đoàn thể','KVXP'=>'Khu vực xã, phường, thị trấn'),
                                null, array('id' => 'level','class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Tên đơn vị quản lý</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>
                                        @if ($value->mabl != NULL)
                                            <a href="{{url('/chuc_nang/bang_luong/in/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</a>
                                            <a href="{{url('/chuc_nang/bang_luong/in_bh/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảo hiểm</a>
                                        @else
                                            <a href="{{url('/chuc_nang/bang_luong/in/maso='.$value->mabl)}}" class="btn btn-danger btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-warning"></i>&nbsp; Đơn vị chưa có bảng lương</a>
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
                window.location.href = '/chuc_nang/tong_hop_luong/ma_so='+$('#madv').val()+'/don_vi';
            });
        })
    </script>
    @include('includes.modal.delete')
@stop