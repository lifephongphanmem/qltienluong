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
                        DANH MỤC KHỐI (TỔ) CÔNG TÁC
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="addPB()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên khối/tổ công tác</th>
                                <th class="text-center" style="width: 30%">Phân loại khối/tổ công tác</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tenpb}}</td>
                                        <td>{{$value->diengiai}}</td>
                                        <td>
                                            <button type="button" onclick="editPB('{{$value->mapb}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                            <button type="button" onclick="cfDel('/danh_muc/phong_ban/del/{{$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin phòng ban -->
    <div id="phongban-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin khối/tổ công tác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label">Mã số<span class="require">*</span></label>
                            {!!Form::text('mapb', null, array('id' => 'mapb','class' => 'form-control','readonly'=>'true'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label">Tên khối/tổ công tác<span class="require">*</span></label>
                            {!!Form::text('tenpb', null, array('id' => 'tenpb','class' => 'form-control','required'=>'required'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-11">
                            <label class="form-control-label">Phân loại khối/tổ công tác</label>
                            {!!Form::select('diengiai', $a_nhompb, null, array('id' => 'diengiai','class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-1" style="padding-left: 0px;">
                            <label class="control-label">&nbsp;&nbsp;&nbsp;</label>
                            <button type="button" class="btn btn-default" data-target="#modal-nhom" data-toggle="modal"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <!-- -->
    <div id="modal-nhom" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin khối/tổ công tác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label">Tên phân loại khối/tổ công tác<span class="require">*</span></label>
                            {!!Form::text('tennhompb', null, array('id' => 'tennhompb','class' => 'form-control','required'=>'required'))!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button class="btn btn-primary" onclick="addnhompb()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addPB(){
            //var date=new Date();
            $('#tenpb').val('');
            $('#mapb').val('');
            $('#id_pb').val(0);
            $('#phongban-modal').modal('show');
        }
        function addnhompb(){
            $('#modal-nhom').modal('hide');
            var gt = $('#tennhompb').val();
            $('#diengiai').append(new Option(gt, gt, true, true));
        }

        function editPB(mapb){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mapb: mapb
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#tenpb').val(data.tenpb);
                    $('#diengiai').trigger(data.diengiai).change;
                    $('#mapb').val(mapb);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            //$('#id_pb').val(id);
            $('#phongban-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var message='';
            var mapb=$('#mapb').val();
            var tenpb=$('#tenpb').val();
            var id=$('#id_pb').val();

            if(tenpb==''){
                valid=false;
                message +='Tên phòng ban không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(mapb==''){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            tenpb: tenpb,
                            diengiai: $('#diengiai').val()
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
                            mapb: mapb,
                            tenpb: tenpb,
                            diengiai: $('#diengiai').val()
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
                $('#phongban-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!.');
            }

            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop