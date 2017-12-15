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
                        <a href="{{url($furl.'ma_so=TT67_2017/in_huyen')}}" class="btn btn-default btn-sm" TARGET="_blank">
                            <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp ({{$soluong}} đơn vị)</a>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label col-md-6" style="text-align: right">Căn cứ thông tư, quyết định </label>
                                <div class="col-md-6">
                                    {!! Form::select('sohieu',getThongTuQD(false),null,array('id' => 'sohieu', 'class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Tên đơn vị tổng hợp dữ liệu</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>{{$value->tendvcq}}</td>
                                    <td>
                                        @if ($value->masodv != NULL)
                                            <a href="{{url('/du_toan/nguon_kinh_phi/ma_so='.$value['masodv'].'/in')}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</a>

                                            <button type="button" class="btn btn-default btn-xs mbs" onclick="confirmChuyen('{{$value['masodv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                Trả lại dữ liệu</button>

                                        @else
                                            <button class="btn btn-danger btn-xs mbs">
                                                <i class="fa fa-warning"></i>&nbsp; Đơn vị chưa tổng hợp dữ liệu</button>
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

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'tralai','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                            <label class="control-label">Lý do trả lại dữ liệu</label>
                            {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <script>
        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        $(function(){
            $('#frm_chuyen :submit').click(function(){
                var chk = true;
                if($('#lydo').val()==''){
                    chk = false;
                }

                //Kết quả
                if ( chk == false){
                    toastr.error('Lý do trả lại không được bỏ trống.','Lỗi!');
                    $("#frm_chuyen").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("#frm_chuyen").unbind('submit').submit();
                }
            });
        });


    </script>


    <script>

        function getLink(){
            var sohieu = $('#sohieu').val();
            var madvbc = $('#madvbc').val();
            return '/chuc_nang/tong_hop_nguon/tinh?sohieu=' + sohieu + '&madiaban=' + madvbc;
        }

        $(function(){
            $('#sohieu').change(function() {
                window.location.href = getLink();
            });

            $('#madvbc').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop