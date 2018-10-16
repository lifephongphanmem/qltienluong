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
    @include('includes.script.scripts')
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
                        <i class="fa fa-list-alt"></i>DANH SÁCH DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới dự toán</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân</br>sách</th>
                                <th class="text-center">Tổng số</th>
                                <th class="text-center">Lương theo</br>ngạch bậc</th>
                                <th class="text-center">Tổng các khoản</br>phụ cấp</th>
                                <th class="text-center">Các khoản</br>đóng góp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td  class="text-center">{{$value->namns}}</td>
                                        <td class="text-right">{{number_format($value->luongnb_dt + $value->luonghs_dt + $value->luongbh_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luongnb_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luonghs_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luongbh_dt)}}</td>
                                        <td>
                                            <a href="{{url($furl.'?maso='.$value->masodv)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>
                                            <!--a href="{{url($furl.'printf/ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In dự toán</a-->
                                            <a href="{{url($furl.'printf_bl/ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</a>
                                            @if($value->trangthai == 'CHUAGUI' || $value->trangthai == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value->masodv}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>

                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif

                                            @if($value->trangthai == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-xs" onclick="getLyDo('{{$value['masodv']}}')" data-target="#tralai-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Lý do trả lại</button>
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

    <!--Modal thêm mới -->

    {!! Form::open(['url'=>$furl.'create', 'id' => 'create_dutoan', 'class'=>'horizontal-form']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin dự toán lương</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Năm được giao dự toán</label>
                            {!!Form::text('namdt', date('Y') + 1, array('id' => 'namdt','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Mức lương cơ bản</label>
                            {!!Form::text('luongcoban', getGeneralConfigs()['luongcb'], array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="confirm_create()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}

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

    <!--Model Trả lại -->
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin lý do trả lại dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>

                    <div class="modal-footer">
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
        function add(){
            $('#create-modal').modal('show');
        }

        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        function getLyDo(masodv){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'getlydo',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    masodv: masodv
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#lydo').val(data.lydo);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            //$('#madvbc').val(madvbc);
            //$('#phongban-modal').modal('show');
        }

        function confirm_create() {
            if ($('#namdt').val() == 0 || $('#namdt').val() == '') {
                toastr.error('Năm dự toán không được bỏ trống.', 'Lỗi!');
                $("form").submit(function (e) {
                    e.preventDefault();
                });
            } else {
                $("form").unbind('submit').submit();
            }
        }

    </script>

    @include('includes.modal.delete')
@stop