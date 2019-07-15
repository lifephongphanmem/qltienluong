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
                        <b>DANH MỤC NGẠCH BẬC LƯƠNG</b>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3%">STT</th>
                                <th class="text-center">Mã nhóm</th>
                                <th class="text-center">Mã ngạch</th>
                                <th class="text-center">Tên ngạch bậc</th>
                                <th class="text-center">Năm</br>nâng</br>bậc</th>
                                <th class="text-center">Hệ</br>số</br>bắt</br>đầu</th>
                                <th class="text-center">Hệ</br>số</br>chênh</br>lệch</th>
                                <th class="text-center">Hệ</br>số</br>lớn</br>nhất</th>
                                <th class="text-center">Bậc</br>lương</br>vượt</br>khung</th>
                                <th class="text-center">Phần</br>trăm</br>vượt</br>khung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->manhom}}</td>
                                        <td>{{$value->msngbac}}</td>
                                        <td>{{$value->tenngachluong}}</td>
                                        <td class="text-center">{{$value->namnb}}</td>
                                        <td class="text-center">{{$value->heso}}</td>
                                        <td class="text-center">{{$value->hesochenhlech}}</td>
                                        <td class="text-center">{{$value->hesolonnhat}}</td>
                                        <td class="text-center">{{$value->bacvuotkhung}}</td>
                                        <td class="text-center">{{$value->vuotkhung}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@stop