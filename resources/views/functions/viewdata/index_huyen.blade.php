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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ BÁO CÁO VÀ ĐƠN VỊ QUẢN LÝ</div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-1 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thang',getThang(),$thang,array('id' => 'thang', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('nam',getNam(),$nam,array('id' => 'nam', 'class' => 'form-control'))!!}
                            </div>
                            <div class="col-md-5">
                                <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                <div class="col-md-7">
                                    {!! Form::select(
                                    'trangthai',$a_trangthai,$trangthai,
                                    array('id' => 'trangthai', 'class' => 'form-control'))
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Phân loại dữ liệu</th>
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
                                    <td>{{$value->tenphanloai}}</td>
                                    <td>
                                        @if ($value->mathdv != NULL)
                                            <a href="{{url('/chuc_nang/tong_hop_luong/huyen/printf_data/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>

                                            <!--a href="{{url('/chuc_nang/tong_hop_luong/huyen/printf_data_diaban/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a-->

                                            @if($value->tralai)
                                                @if(session('admin')->phamvitonghop == 'KHOI')
                                                    <button type="button" class="btn btn-default btn-sm" onclick="confirmChuyen('{{$value['mathdv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                        Trả lại dữ liệu</button>
                                                @endif
                                                @if(session('admin')->phamvitonghop == 'HUYEN')
                                                    <button type="button" class="btn btn-default btn-sm" onclick="confirmChuyen('{{$value['mathh']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                        Trả lại dữ liệu</button>
                                                @endif
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
                {!! Form::open(['url'=>$furl.'huyen/tralai','id' => 'frm_chuyen','method'=>'POST'])!!}
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
                    <input type="hidden" name="mathdv" id="mathh">
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
        function confirmChuyen(math) {
            document.getElementById("mathdv").value = math;
            document.getElementById("mathh").value = math;
        }

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