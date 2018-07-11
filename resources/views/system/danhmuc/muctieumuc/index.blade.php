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
                        <b>DANH MỤC MỤC - TIỂU MỤC</b>
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tiểu mục</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Sự nghiệp</br>cán bộ</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Phụ cấp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tieumuc}}</td>
                                        <td>{{$value->noidung}}</td>
                                        <td>{{$value->tensunghiep}}</td>
                                        <td>{{$value->tennhomct}}</td>
                                        <td>{{$value->tenpc}}</td>
                                        <td>
                                            <button type="button" onclick="editCV('{{$value->tieumuc}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin chức vụ -->
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin tiểu mục</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Mục<span class="require">*</span></label>
                                {!!Form::text('muc', null, array('id' => 'muc','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Tiểu mục<span class="require">*</span></label>
                                {!!Form::text('tieumuc', null, array('id' => 'tieumuc','class' => 'form-control','required'=>'required'))!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Nội dung tiểu mục</label>
                                {!!Form::text('noidung', null, array('id' => 'noidung','class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Sự nghiệp</label>
                                {!! Form::select('sunghiep',$model_sunghiep,null,array('id'=>'sunghiep','class'=>'form-control select2me'))!!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Phân loại công tác</label>
                                {!! Form::select('macongtac',$model_nhomct,null,array('id'=>'macongtac','class'=>'form-control select2me'))!!}

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Phụ cấp</label>
                                {!! Form::select('mapc',$model_phucap,null,array('id' => 'mapc','class' => 'form-control select2me')) !!}
                            </div>
                        </div>
                    </div>
                    <!--input type="hidden" id="id" name="id"/-->
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
            $('#muc').val('');
            $('#tieumuc').val('');
            $('#noidung').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
        }

        function editCV(tieumuc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    tieumuc: tieumuc
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#muc').val(data.muc);
                    $('#tieumuc').val(data.tieumuc);
                    $('#noidung').val(data.noidung);
                    $('#sunghiep').val(data.sunghiep).trigger('change');
                    $('#macongtac').val(data.macongtac).trigger('change');
                    $('#mapc').val(data.mapc).trigger('change');
                    //$('#id').val(data.muc);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');

        }

        function cfPB() {
            var valid = true;
            var message = '';

            var muc = $('#muc').val();
            var tieumuc = $('#tieumuc').val();

            if (muc == '' || tieumuc == '') {
                valid = false;
                message += 'Mục - Tiểu mục không được bỏ trống \n';
            }

            if (valid) {
                $.ajax({
                    url: '{{$furl}}' + 'store',
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        muc: muc,
                        tieumuc: tieumuc,
                        noidung: $('#noidung').val(),
                        sunghiep: $('#sunghiep').val(),
                        macongtac: $('#macongtac').val(),
                        mapc: $('#mapc').val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 'success') {
                            location.reload();
                        }
                    },
                    error: function (message) {
                        toastr.error(message);
                    }
                });

                $('#create-modal').modal('hide');
            } else {
                toastr.error(message, 'Lỗi!.');
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop