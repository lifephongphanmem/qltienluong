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

                                            <!--button type="button" onclick="inbl('{{$value->maso}}','{{$value->thang}}','{{$value->nam}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-share-square-o"></i>&nbsp; Gửi dữ liệu</button-->

                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal chinh sửa -->
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin dự toán lương</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <label class="control-label">Năm được giao</label>
                        {!!Form::text('namns', null, array('id' => 'namns','class' => 'form-control text-right','readonly'=>'true'))!!}

                        <label class="control-label">Lương theo ngạch bậc dự toán</label>
                        {!!Form::text('luongnb_dt', null, array('id' => 'luongnb_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}

                        <label class="control-label">Tổng các khoản phụ cấp dự toán</label>
                        {!!Form::text('luonghs_dt', null, array('id' => 'luonghs_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}

                        <label class="control-label">Các khoản đóng góp dự toán</label>
                        {!!Form::text('luongbh_dt', null, array('id' => 'luongbh_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}

                    </div>
                    <input type="hidden" id="id_ct" name="id_ct"/>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="confirm()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function add(){
            $('#create-modal').modal('show');
        }

        function edit(id){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_ajax}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#namns').val(data.namns);
                    $('#luongnb_dt').val(data.luongnb_dt);
                    $('#luonghs_dt').val(data.luonghs_dt);
                    $('#luongbh_dt').val(data.luongbh_dt);
                },
                error: function (message) {
                    toastr.error(message, 'Lỗi!');
                }
            });

            $('#id_ct').val(id);
            $('#chitiet-modal').modal('show');
        }

        function confirm(){
            if( $('#namns').val() == 0 || $('#nam').val() ==''){
                toastr.error('Năm dự toán không được bỏ trống.', 'Lỗi!');
                return false;
            }
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_ajax}}' + 'update',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    namns:$('#namns').val(),
                    luongnb_dt:$('#luongnb_dt').val(),
                    luonghs_dt:$('#luonghs_dt').val(),
                    luongbh_dt:$('#luongbh_dt').val(),
                    id: $('#id_ct').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        location.reload();
                    }
                },
                error: function(message){
                    toastr.error(message, 'Lỗi!');
                }
            });
            $('#chitiet-modal').modal('hide');
            //Trả lại kết quả
            return true;
        }

        $(function(){
            $('#namdt').change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$furl}}' + 'checkNamDuToan',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        namdt: $(this).val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.status == 'false'){
                            toastr.error(data.message, 'Lỗi!');
                            $('#namdt').val(0);
                            $('#namdt').focus();
                        }
                    },
                    error: function (message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });

            });
        });

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