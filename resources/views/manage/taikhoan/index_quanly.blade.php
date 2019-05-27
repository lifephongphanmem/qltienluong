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
        function confirmStop(id) {
            document.getElementById("madvstop").value =id;
        }
        function confirmActive(id) {
            document.getElementById("madvactive").value =id;
        }
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ BÁO CÁO VÀ ĐƠN VỊ QUẢN LÝ</div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Ngày tháng dừng HĐ</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;?>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>{{$value->ngaydung}}</td>
                                    <td>
                                        @if($value->trangthai != 'TD')
                                            <button type="button" class="btn btn-default btn-sm" onclick="confirmStop('{{$value['madv']}}')" data-target="#chuyen-stop" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                Dừng HĐ</button>
                                        @else
                                            <button class="btn btn-danger btn-xs mbs">
                                                <i class="fa fa-warning"></i>&nbsp; Đơn vị đã dừng hoạt động</button>
                                            <button type="button" class="btn btn-default btn-sm" onclick="confirmActive('{{$value['madv']}}')" data-target="#chuyen-active" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                Hoạt động</button>
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

    <div class="modal fade" id="chuyen-stop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'stopdv/stop','id' => 'frm_stop','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý dừng hoạt động đơn vị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="tthschuyen">
                    </div>
                    <div class="form-group">
                        <label><b>Ngày dừng hoạt động</b></label>
                        <input type="date" name="ngaydung" id="ngaydung" class="form-control" />
                    </div>
                </div>
                <input type="hidden" name="madvstop" id="madvstop">
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn blue" >Đồng ý</button>

                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="chuyen-active" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'stopdv/active','id' => 'frm_active','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý hoạt động đơn vị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Ngày hoạt động</b></label>
                        <input type="date" name="ngaydung" id="ngaydung" class="form-control" />
                    </div>
                </div>
                <input type="" name="madvactive" id="madvactive">
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn blue" >Đồng ý</button>

                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script>

        function getLink(){
            var thang = $('#thang').val();
            var nam = $('#nam').val();
            var trangthai = $('#trangthai').val();
            return '/chuc_nang/xem_du_lieu/huyen?thang='+ thang +'&nam=' + nam + '&trangthai=' + trangthai;
        }

        $(function(){
            $('#thang').change(function() {
                window.location.href = getLink();
            });
            $('#nam').change(function() {
                window.location.href = getLink();
            });
            $('#trangthai').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop