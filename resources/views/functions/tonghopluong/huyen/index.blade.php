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
                        DANH SÁCH DỮ LIỆU TỔNG HỢP LƯƠNG TỪ ĐƠN VỊ CẤP DƯỚI
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Đơn vị</br>gửi số</br>liệu</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;?>
                        @if(isset($model))
                            @foreach($model as $value)
                                <tr class="{{getTextStatus($value['trangthai'])}}">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-center">{{$value['thang'].'/'.$nam}}</td>
                                    <td>{{$value['noidung']}}</td>
                                    <td class="text-center bold"> {{$value['dvgui'].'/'.$value['sldv']}}</td>
                                    <td class="text-center bold">{{$a_trangthai[$value['trangthai']]}}</td>
                                    <td>
                                        @if ($value['mathdv'] != NULL)
                                            <a href="{{url($furl.'tonghop?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-xs" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                            <a href="{{url($furl.'tonghop_diaban?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-xs" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a>
                                            <a href="{{url('/chuc_nang/xem_du_lieu/huyen?thang='.$value['thang'].'&nam='.$nam.'&trangthai=ALL')}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>

                                        @else
                                            @if($value['trangthai'] != 'CHUADL')
                                                <a href="{{url($furl.'tonghop?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-xs" target="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                                <a href="{{url($furl.'tonghop_diaban?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-xs" target="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a>

                                                @if($value['trangthai'] != 'CHUADAYDU')
                                                    <a href="{{url('/chuc_nang/xem_du_lieu/index?thang='.$value['thang'].'&nam='.$nam.'&trangthai=ALL')}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>
                                                    <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value['thang']}}','{{$nam}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                        Gửi dữ liệu</button>
                                                @else
                                                    <a href="{{url('/chuc_nang/xem_du_lieu/index?thang='.$value['thang'].'&nam='.$nam.'&trangthai=CHOGUI')}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Đơn vị chưa gửi dữ liệu</a>
                                                @endif

                                            @else
                                                <a href="{{url('/chuc_nang/xem_du_lieu/huyen?thang='.$value['thang'].'&nam='.$nam.'&trangthai=ALL')}}" class="btn btn-default btn-xs">
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
                {!! Form::open(['url'=>$furl.'senddata','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi gửi.</b></label>
                    </div>
                    <input type="hidden" name="thang" id="thang">
                    <input type="hidden" name="nam" id="nam">
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

    <!--Model tổng hợp dữ liệu -->
    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>'','id' => 'frm_tonghop','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý tổng hợp số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Một số đơn vị cấp dưới chưa gửi dữ liệu tổng hợp.</br>Bạn có chắc chắn muốn tổng hợp số liệu ?</b></label>
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
    @include('includes.modal.delete')
    <script>
        function confirmChuyen(thang, nam) {
            document.getElementById("thang").value = thang;
            document.getElementById("nam").value = nam;
        }

        function confirmTonghop(url) {
            $('#frm_tonghop').attr('action', url);
        }
    </script>

@stop