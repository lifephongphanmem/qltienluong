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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ CẤP DƯỚI</div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <div class="col-md-4">
                                    <label class="control-label " style="text-align: right">Tháng </label>
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('thang',getThang(),$thang,array('id' => 'thang', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="col-md-3">
                                    <label class="control-label " style="text-align: right">Năm </label>
                                </div>
                                <div class="col-md-9">
                                    {!! Form::select('nam',getNam(),$nam, array('id' => 'nam', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="col-md-4">
                                    <label class="control-label " style="text-align: right">Trạng thái </label>
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('trangthai',$a_trangthai,$trangthai,array('id' => 'trangthai', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="col-md-5">
                                    <label class="control-label " style="text-align: right">Phân loại đơn vị</label>
                                </div>
                                <div class="col-md-7">
                                    {!! Form::select('phanloai',$a_phanloai,$phanloai, array('id' => 'phanloai', 'class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ngày gửi</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>{{$value->trangthai}}</td>
                                    <td>{{getDayVn($value->ngaygui)}}</td>
                                    <td>
                                        @if ($value->mathdv != NULL)
                                            @if($value->phanloaitaikhoan == 'TH')
                                                <a href="{{url('/chuc_nang/tong_hop_luong/khoi/tonghop_khoi?thangbc='.$value['thang'].'&nambc='.$nam.'&madv='.$value['madv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp khối</a>
                                                <a href="{{url('/chuc_nang/tong_hop_luong/don_vi/printf_bl_khoi/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết khối</a>
                                            @else
                                                <a href="{{url('/chuc_nang/tong_hop_luong/khoi/printf_data/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                                <!--a href="{{url('/chuc_nang/tong_hop_luong/don_vi/printf_bl/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</a-->
                                                <button type="button" onclick="inbl('{{$value['mathdv']}}','{{$value['madv']}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</button>
                                            @endif

                                            @if($value->maphanloai == 'KVXP')
                                                <!--a href="{{url('/chuc_nang/tong_hop_luong/don_vi/printf_data_diaban/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a-->
                                            @endif
                                            @if($value->trangthai == 'CHOKHOINHAN')
                                                <button type="button" class="btn btn-default btn-sm" onclick="confirmNhan('{{$value['mathdv']}}')" data-target="#nhan-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                    Nhận dữ liệu</button>
                                            @endif
                                            @if($value->tralai)
                                                <button type="button" class="btn btn-default btn-sm" onclick="confirmChuyen('{{$value['mathdv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                    Trả lại dữ liệu</button>
                                            @endif
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
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'don_vi/tralai','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>
                    <input type="hidden" name="mathdv" id="mathdv">
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

    <div class="modal fade" id="nhan-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'khoi/nhan','id' => 'frm_nhan','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý nhận số liệu?</h4>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Ngày nhận dữ liệu</label>
                            <input type="date" name="ngaykhoinhan_nhan" id="ngaykhoinhan_nhan" class="form-control" value="{{date('Y-m-d')}}"/>
                            <input type="hidden" name="mathdv_nhan" id="mathdv_nhan">
                        </div>
                    </div>
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
    <div id="inbl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu chi tiết</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_bl" href="" onclick="insolieu('/chuc_nang/tong_hop_luong/khoi/printf_bl_khoi')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết</a>
                        </div>
                    </div>

                    <!--div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blCR" href="" onclick="insolieuCR('/chuc_nang/tong_hop_luong/huyen/printf_bl_huyenCR')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết (CR)</a>
                        </div>
                    </div-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blkhoito" href="" onclick="inkhoito('/chuc_nang/tong_hop_luong/khoi/inkhoito')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết (khối tổ)</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_thkhoito" href="" onclick="inthkhoito('/chuc_nang/tong_hop_luong/khoi/inkhoito_th')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu tổng hợp (khối tổ)</a>
                        </div>
                    </div>
                </div>
                <!--div class="row">
                    <!--div class="col-md-6">
                        <div class="form-group">
                            <a id="in_ttCR" href="" onclick="thanhtoanCR('/chuc_nang/tong_hop_luong/huyen/thanh_toan_CR')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In bảng thanh toán lương (CR)</a>
                        </div>
                    </div>

                </div-->
            </div>
            <input type="hidden" id="mathdv_in" name="mathdv_in"/>
            <input type="hidden" id="madv_in" name="madv_in"/>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>
    <script>
        function confirmChuyen(mathdv) {
            document.getElementById("mathdv").value = mathdv;
        }

        function confirmNhan(mathdv) {
            document.getElementById("mathdv_nhan").value = mathdv;
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
    function inbl(mathdv,madv){
        $("#mathdv_in").val(mathdv);
        $("#madv_in").val(madv);
        $('#inbl-modal').modal('show');
        //$('#inbl-modal').modal('hide');
    }
    function insolieu($url){
        $("#in_bl").attr("href", $url +'?mathdv='+$('#mathdv_in').val()+'&madv='+ $('#madv_in').val());
    }
    function insolieuCR($url){
        $("#in_blCR").attr("href", $url +'?mathdv='+$('#mathdv_in').val()+'&madv='+ $('#madv_in').val());
    }
    function thanhtoanCR($url){
        $("#in_ttCR").attr("href", $url +'?mathdv='+$('#mathdv_in').val()+'&madv='+ $('#madv_in').val());
    }
    function inkhoito($url){
        $("#in_blkhoito").attr("href", $url +'?mathdv='+$('#mathdv_in').val()+'&madv='+ $('#madv_in').val());
    }
    function inthkhoito($url){
        $("#in_thkhoito").attr("href", $url +'?mathdv='+$('#mathdv_in').val()+'&madv='+ $('#madv_in').val());
    }
    </script>
    <script>

        function getLink(){
            var thang = $('#thang').val();
            var nam = $('#nam').val();
            var trangthai = $('#trangthai').val();
            var phanloai = $('#phanloai').val();
            return '/chuc_nang/xem_du_lieu/index?thang='+ thang +'&nam=' + nam + '&trangthai=' + trangthai + '&phanloai=' + phanloai;
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
            $('#phanloai').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop