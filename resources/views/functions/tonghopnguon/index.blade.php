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
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Căn cứ thông tư, quyết định</th>
                            <th class="text-center">Đơn vị gửi</br>số liệu</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tenttqd}}</td>
                                    <td class="text-center">{{$value->sldv}}</td>
                                    <td class="text-center bold">{{$a_trangthai[$value['trangthai']]}}</td>
                                    <td>
                                        @if ($value['masodv'] != NULL)
                                            <a href="{{url('/du_toan/nguon_kinh_phi/ma_so='.$value['masodv'].'/in')}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</a>

                                            <a href="{{url('/chuc_nang/xem_du_lieu/index?sohieu='.$value->sohieu)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>

                                            @if($value['trangthai'] == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-sm" onclick="getLyDo('{{$value['masodv']}}')" data-target="#tralai-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Lý do trả lại</button>
                                                <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value->sohieu}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>
                                            @endif

                                        @else
                                            @if($value['trangthai'] != 'CHUADL')
                                                <a href="{{url($furl.'tonghop?sohieu='.$value->sohieu)}}" class="btn btn-default btn-xs" target="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>

                                                <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value->sohieu}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>
                                                @if($value['trangthai'] != 'CHUADAYDU')
                                                    <a href="{{url('/chuc_nang/xem_du_lieu/index??sohieu='.$value->sohieu)}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>
                                                    <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value->sohieu}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                        Gửi dữ liệu</button>
                                                @else
                                                    <a href="{{url('/chuc_nang/xem_du_lieu/index??sohieu='.$value->sohieu.'&trangthai=CHOGUI')}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Đơn vị chưa gửi dữ liệu</a>
                                                @endif


                                            @else
                                                <a href="{{url('/chuc_nang/xem_du_lieu/index??sohieu='.$value->sohieu.'&trangthai=ALL')}}" class="btn btn-default btn-xs">
                                                    <i class="fa fa-stack-overflow"></i>&nbsp; Chưa có dữ liệu</a>
                                            @endif
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
            return '/chuc_nang/tong_hop_nguon/index?sohieu=' + sohieu;
        }

        $(function(){
            $('#sohieu').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop