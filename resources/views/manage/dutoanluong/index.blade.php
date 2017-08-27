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
                        <i class="fa fa-list-alt"></i>DANH SÁCH DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ
                    </div>
                    @include('includes.crumbs.bt_add')
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân sách</th>
                                <th class="text-center">Tổng số</th>
                                <th class="text-center">Lương theo ngạch bậc</th>
                                <th class="text-center">Tổng các khoản phụ cấp</th>
                                <th class="text-center">Các khoản đóng góp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->namns}}</td>
                                        <td>{{number_format($value->luongnb_dt + $value->luonghs_dt + $value->luongbh_dt)}}</td>
                                        <td>{{number_format($value->luongnb_dt)}}</td>
                                        <td>{{number_format($value->luonghs_dt)}}</td>
                                        <td>{{number_format($value->luongbh_dt)}}</td>
                                        @include('includes.crumbs.bt_editdel')
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
    </div>

    <!--Modal thông tin chi tiết -->
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin hồ sơ luân chuyển, điều động cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Năm được giao</label>
                            <div class="col-md-8">
                                {!!Form::text('namns', null, array('id' => 'namns','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <label class="col-md-4 control-label">Lương theo ngạch bậc năm trước</label>
                            <div class="col-md-8">
                                {!!Form::text('luongnb', null, array('id' => 'luongnb','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tổng các khoản phụ cấp năm trước</label>
                            <div class="col-md-8">
                                {!!Form::text('luonghs', null, array('id' => 'luonghs','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Các khoản đóng góp năm trước</label>
                            <div class="col-md-8">
                                {!!Form::text('luongbh', null, array('id' => 'luongbh','class' => 'form-control'))!!}
                            </div>
                        </div>
                        -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Lương theo ngạch bậc dự toán</label>
                            <div class="col-md-8">
                                {!!Form::text('luongnb_dt', null, array('id' => 'luongnb_dt','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tổng các khoản phụ cấp dự toán</label>
                            <div class="col-md-8">
                                {!!Form::text('luonghs_dt', null, array('id' => 'luonghs_dt','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Các khoản đóng góp dự toán</label>
                            <div class="col-md-8">
                                {!!Form::text('luongbh_dt', null, array('id' => 'luongbh_dt','class' => 'form-control'))!!}
                            </div>
                        </div>


                        <input type="hidden" id="id_ct" name="id_ct"/>
                    </div>
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
            var macb=$('#cbmacb').val();
            if(macb=='all'){
                alert('Bạn cần chọn cán bộ để nhập thông tin.');
                $('#cbmacb').focus();
            }else{
                $('#namns').val(2017);
                //$('#luongnb').val(0);
                //$('#luonghs').val(0);
                //$('#luongbh').val(0);
                $('#luongnb_dt').val(0);
                $('#luonghs_dt').val(0);
                $('#luongbh_dt').val(0);
                $('#id_ct').val(0);
                $('#chitiet-modal').modal('show');
            }
        }

        function getInfo(){
            window.location.href = '{{$furl}}'+'maso='+$('#cbmacb').val();
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
                    //$('#luongnb').val(data.luongnb);
                    //$('#luonghs').val(data.luonghs);
                    //$('#luongbh').val(data.luongbh);
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
            var valid=true;
            var message='';

            var id=$('#id_ct').val();


            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            namns:$('#namns').val(),
                            //luongnb:$('#luongnb').val(),
                            //luonghs:$('#luonghs').val(),
                            //luongbh:$('#luongbh').val(),
                            luongnb_dt:$('#luongnb_dt').val(),
                            luonghs_dt:$('#luonghs_dt').val(),
                            luongbh_dt:$('#luongbh_dt').val()
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
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            namns:$('#namns').val(),
                            //luongnb:$('#luongnb').val(),
                            //luonghs:$('#luonghs').val(),
                            //luongbh:$('#luongbh').val(),
                            luongnb_dt:$('#luongnb_dt').val(),
                            luonghs_dt:$('#luonghs_dt').val(),
                            luongbh_dt:$('#luongbh_dt').val(),
                            id: id
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
                }
                $('#chitiet-modal').modal('hide');
            }else{
                toastr.error(message, 'Lỗi!');
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop