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
                        <i class="fa fa-list-alt"></i>DANH SÁCH CHỈ TIÊU BIÊN CHẾ CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới chỉ tiêu</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <input type="hidden" id="soluongcanbo" name="soluongcanbo" value="{{$soluongcanbo}}">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm được giao</th>
                                <th class="text-center">Số lượng biên</br>chế được giao</th>
                                <th class="text-center">Số lượng biên</br>chế hiện có</th>
                                <th class="text-center">Số lượng cán</br>bộ không chuyên<br>trách(nếu có)</th>
                                <th class="text-center">Số lượng đại</br> biểu HĐND</th>
                                <th class="text-center">Số lượng ủy viên</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr class="text-center">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->nam}}</td>
                                        <td>{{$value->soluongduocgiao}}</td>
                                        <td>{{$value->soluongbienche}}</td>
                                        <td>{{$value->soluongkhongchuyentrach}}</td>
                                        <td>{{$value->soluonguyvien}}</td>
                                        <td>{{$value->soluongdaibieuhdnd}}</td>
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
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chỉ tiêu cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <label class="control-label">Năm được giao</label>
                        {!!Form::text('nam', null, array('id' => 'nam','class' => 'form-control text-right'))!!}

                        <label class="control-label">Số lượng biên chế được giao</label>
                        {!!Form::text('soluongduocgiao', null, array('id' => 'soluongduocgiao','class' => 'form-control text-right'))!!}

                        <label class="control-label">Số lượng biên chế hiện có</label>
                        {!!Form::text('soluongbienche', null, array('id' => 'soluongbienche','class' => 'form-control text-right'))!!}

                        <label class="control-label">Số lượng cán bộ không chuyên trách (nếu có)</label>
                        {!!Form::text('soluongkhongchuyentrach', null, array('id' => 'soluongkhongchuyentrach','class' => 'form-control text-right'))!!}

                        <label class="control-label">Số lượng ủy viên</label>
                        {!!Form::text('soluonguyvien', null, array('id' => 'soluonguyvien','class' => 'form-control text-right'))!!}

                        <label class="control-label">Số lượng đại biểu HĐND</label>
                        {!!Form::text('soluongdaibieuhdnd', null, array('id' => 'soluongdaibieuhdnd','class' => 'form-control text-right'))!!}

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
        $(function(){
            $('#nam').change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$furl_ajax}}' + 'getNamChiTieu',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        nam: $(this).val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if(data !='true'){
                            toastr.error('Năm chỉ tiêu đã tồn tại. \n Bạn cần nhập lại năm khác.', 'Lỗi!');
                            $('#nam').val(0);
                            $('#nam').focus();
                        }
                    },
                    error: function (message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });
            });
        });
        function add(){
            $('#nam').prop('readonly',false);
            $('#nam').val(0);
            $('#soluongduocgiao').val(0);
            $('#soluongbienche').val($('#soluongcanbo').val());
            $('#soluongkhongchuyentrach').val(0);
            $('#soluonguyvien').val(0);
            $('#soluongdaibieuhdnd').val(0);
            $('#id_ct').val(0);
            $('#chitiet-modal').modal('show');
        }

        function edit(id){
            $('#nam').prop('readonly',true);
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
                    $('#nam').val(data.nam);
                    $('#soluongduocgiao').val(data.soluongduocgiao);
                    $('#soluongbienche').val(data.soluongbienche);
                    $('#soluongkhongchuyentrach').val(data.soluongkhongchuyentrach);
                    $('#soluonguyvien').val(data.soluonguyvien);
                    $('#soluongdaibieuhdnd').val(data.soluongdaibieuhdnd);
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
            if( $('#nam').val() == 0 || $('#nam').val() ==''){
                toastr.error('Năm chỉ tiêu không được bỏ trống.', 'Lỗi!');
                return false;
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            nam:$('#nam').val(),
                            soluongduocgiao:$('#soluongduocgiao').val(),
                            soluongbienche:$('#soluongbienche').val(),
                            soluongkhongchuyentrach:$('#soluongkhongchuyentrach').val(),
                            soluonguyvien:$('#soluonguyvien').val(),
                            soluongdaibieuhdnd:$('#soluongdaibieuhdnd').val()
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
                            nam:$('#nam').val(),
                            soluongduocgiao:$('#soluongduocgiao').val(),
                            soluongbienche:$('#soluongbienche').val(),
                            soluongkhongchuyentrach:$('#soluongkhongchuyentrach').val(),
                            soluonguyvien:$('#soluonguyvien').val(),
                            soluongdaibieuhdnd:$('#soluongdaibieuhdnd').val(),
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