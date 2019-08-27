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
                        <b>DANH MỤC NHÓM PHÂN LOẠI CÔNG TÁC</b>
                    </div>
                    <div class="actions">
                        @if(session('admin')->level == 'SSA')
                            <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencongtac}}</td>
                                        <td>{{$value->ghichu}}</td>
                                        <td>
                                            <a href="{{url($furl.'ma_so='.$value->macongtac)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                            @if(can('dmphanloaict','edit'))
                                                <button type="button" onclick="editCV('{{$value->macongtac}}')" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif

                                            @if(can('dmphanloaict','delete'))
                                                <button type="button" onclick="cfDel('/danh_muc/cong_tac/del/{{$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal thông tin chức vụ -->
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin phân loại công tác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 5px;">
                            <label class="form-control-label">Phân loại công tác<span class="require">*</span></label>
                            {!!Form::text('tencongtac', null, array('id' => 'tencongtac','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}
                        </div>

                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Phần trăm bảo hiểm cá nhân nộp
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH xã hội </label>
                                                {!!Form::text('bhxh', 0, array('id' => 'bhxh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt', 0, array('id' => 'bhyt','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn', 0, array('id' => 'bhtn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd', 0, array('id' => 'kpcd','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>

                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Phần trăm bảo hiểm đơn vị nộp
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH xã hội </label>
                                                {!!Form::text('bhxh_dv', 0, array('id' => 'bhxh_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt_dv', 0, array('id' => 'bhyt_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn_dv', 0, array('id' => 'bhtn_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd_dv', 0, array('id' => 'kpcd_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>

                        <div class="col-md-12">
                            <label class="form-control-label">Ghi chú</label>
                            {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id"/>
                    <input type="hidden" id="macongtac" name="macongtac"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function add(){
            $('#tencongtac').val('');
            $('#maphanloai').val('');
            $('#macongtac').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
        }

        function editCV(macongtac){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macongtac: macongtac
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#maphanloai').val(data.maphanloai);
                    $('#tencongtac').val(data.tencongtac);
                    $('#macongtac').val(data.macongtac);
                    $('#ghichu').val(data.ghichu);
                    $('#bhxh').val(data.bhxh);
                    $('#bhyt').val(data.bhyt);
                    $('#kpcd').val(data.kpcd);
                    $('#bhtn').val(data.bhtn);
                    $('#bhxh_dv').val(data.bhxh_dv);
                    $('#bhyt_dv').val(data.bhyt_dv);
                    $('#kpcd_dv').val(data.kpcd_dv);
                    $('#bhtn_dv').val(data.bhtn_dv);
                    $('#id').val(data.id);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');

        }

        function cfPB(){
            var valid=true;
            var message='';

            var id = $('#id').val();
            var maphanloai = $('#maphanloai').val();
            var tencongtac = $('#tencongtac').val();
            var macongtac = $('#macongtac').val();
            var ghichu=$('#ghichu').val();

            if(tencongtac==''){
                valid=false;
                message +='Tên phân loại công tác không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            maphanloai: maphanloai,
                            macongtac: macongtac,
                            tencongtac: tencongtac,
                            bhxh: $('#bhxh').val(),
                            bhyt: $('#bhyt').val(),
                            kpcd: $('#kpcd').val(),
                            bhtn: $('#bhtn').val(),
                            bhxh_dv: $('#bhxh_dv').val(),
                            bhyt_dv: $('#bhyt_dv').val(),
                            kpcd_dv: $('#kpcd_dv').val(),
                            bhtn_dv: $('#bhtn_dv').val(),
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            macongtac: macongtac,
                            maphanloai: maphanloai,
                            tencongtac: tencongtac,
                            bhxh: $('#bhxh').val(),
                            bhyt: $('#bhyt').val(),
                            kpcd: $('#kpcd').val(),
                            bhtn: $('#bhtn').val(),
                            bhxh_dv: $('#bhxh_dv').val(),
                            bhyt_dv: $('#bhyt_dv').val(),
                            kpcd_dv: $('#kpcd_dv').val(),
                            bhtn_dv: $('#bhtn_dv').val(),
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message,'Lỗi!!');
                        }
                    });
                }
                $('#create-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!.');
            }

            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop