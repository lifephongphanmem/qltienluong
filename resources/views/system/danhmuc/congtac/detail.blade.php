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
                        <b>DANH MỤC PHÂN LOẠI CÔNG TÁC CHI TIẾT</b>
                    </div>
                    @if(can('dmphanloaict','create'))
                        <div class="actions">
                            <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        </div>
                    @endif
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Tổng hợp số liệu</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tenct}}</td>
                                        <td class="text-center">{{$value->tonghop == 1 ? 'Tổng hợp và dự toán':''}}</td>
                                        <td>
                                            @if(can('dmphanloaict','edit'))
                                                <button type="button" onclick="editCV('{{$value->mact}}')" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif

                                            @if(can('dmphanloaict','delete'))
                                                <button type="button" onclick="cfDel('/danh_muc/cong_tac/del_detail/{{$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-offset-5 col-md-8">
                            <a href="{{url('/danh_muc/cong_tac/index')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>

                        </div>
                    </div>
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
                            {!!Form::text('tenct', null, array('id' => 'tenct','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 5px;">
                            <label class="form-control-label">Tổng hợp và dự toán</label>
                            {!!Form::select('tonghop',array('0'=>'Không','1'=>'Có'), null, array('id' => 'tonghop','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="row">
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
                                                {!!Form::text('bhxh', $m_nhom->bhxh, array('id' => 'bhxh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt', $m_nhom->bhyt, array('id' => 'bhyt','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn', $m_nhom->bhtn, array('id' => 'bhtn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd', $m_nhom->kpcd, array('id' => 'kpcd','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                                                {!!Form::text('bhxh_dv', $m_nhom->bhxh_dv, array('id' => 'bhxh_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt_dv', $m_nhom->bhyt_dv, array('id' => 'bhyt_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn_dv', $m_nhom->bhtn_dv, array('id' => 'bhtn_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd_dv', $m_nhom->kpcd_dv, array('id' => 'kpcd_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>


                    </div>
                    <input type="hidden" id="id" name="id"/>
                    <input type="hidden" id="mact" name="mact"/>
                    <input type="hidden" id="macongtac" name="macongtac" value="{{$macongtac}}"/>
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
            $('#tenct').val('');
            $('#mact').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
            $('#tenct').focus();
        }

        function editCV(mact){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_detail',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mact: mact
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mact').val(data.mact);
                    $('#tenct').val(data.tenct);
                    $('#tonghop').val(data.tonghop).trigger("change");
                    $('#id').val(data.id);
                    $('#bhxh').val(data.bhxh);
                    $('#bhyt').val(data.bhyt);
                    $('#kpcd').val(data.kpcd);
                    $('#bhtn').val(data.bhtn);
                    $('#bhxh_dv').val(data.bhxh_dv);
                    $('#bhyt_dv').val(data.bhyt_dv);
                    $('#kpcd_dv').val(data.kpcd_dv);
                    $('#bhtn_dv').val(data.bhtn_dv);
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
            var mact = $('#mact').val();
            var tenct = $('#tenct').val();
            var macongtac = $('#macongtac').val();

            if(tenct==''){
                valid=false;
                message +='Tên phân loại công tác không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add_detail',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mact: mact,
                            macongtac: macongtac,
                            tonghop: $('#tonghop').val(),
                            tenct: tenct,
                            bhxh:$('#bhxh').val(),
                            bhyt:$('#bhyt').val(),
                            kpcd:$('#kpcd').val(),
                            bhtn:$('#bhtn').val(),
                            bhxh_dv:$('#bhxh_dv').val(),
                            bhyt_dv:$('#bhyt_dv').val(),
                            kpcd_dv:$('#kpcd_dv').val(),
                            bhtn_dv:$('#bhtn_dv').val()
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }else{
                                toastr.error(data.message);
                            }
                        },
                        error: function(message){
                            toastr.error(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update_detail',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mact: mact,
                            tenct: tenct,
                            tonghop: $('#tonghop').val(),
                            bhxh:$('#bhxh').val(),
                            bhyt:$('#bhyt').val(),
                            kpcd:$('#kpcd').val(),
                            bhtn:$('#bhtn').val(),
                            bhxh_dv:$('#bhxh_dv').val(),
                            bhyt_dv:$('#bhyt_dv').val(),
                            kpcd_dv:$('#kpcd_dv').val(),
                            bhtn_dv:$('#bhtn_dv').val()
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