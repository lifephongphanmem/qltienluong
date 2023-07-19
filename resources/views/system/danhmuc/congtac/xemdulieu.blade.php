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
                        <b>DANH MỤC PHÂN LOẠI CÔNG TÁC CHI TIẾT</b>
                    </div>                   
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên nhóm</th>
                                <th class="text-center">Tên phân loại công tác</th>
                                <th style="width: 10%" class="text-center">Tổng hợp</br>số liệu</th>
                                <th style="width: 10%" class="text-center">Dự toán</br>lương</th>
                                <th style="width: 10%" class="text-center">Nhu cầu</br>kinh phí</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$a_nhomct[$value->macongtac] ?? ''}}</td>
                                        <td>{{$value->tenct}}</td>
                                        <td class="text-center">{!! $value->tonghop == 1 ? '<i class="fa fa-check"></i>' : '' !!} </td>
                                        <td class="text-center">{!! $value->dutoan == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>                                        
                                        <td class="text-center">{!! $value->nhucaukp == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>                                        
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