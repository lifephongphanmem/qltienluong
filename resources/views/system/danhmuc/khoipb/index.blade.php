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
                        DANH MỤC LĨNH VỰC HOẠT ĐỘNG
                    </div>
                    <div class="actions">
                        @if(can('dmlinhvuchd','create'))
                            <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="addPB()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Mã số</th>
                                <th class="text-center">Lĩnh vực hoạt động</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->makhoipb}}</td>
                                        <td>{{$value->tenkhoipb}}</td>
                                        <td>{{$value->ghichu}}</td>
                                        <td>
                                            @if(can('dmlinhvuchd','edit'))
                                                <button type="button" onclick="edit('{{$value->id}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif

                                            @if(can('dmlinhvuchd','delete'))
                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin phòng ban -->
    <div id="phongban-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin khối phòng ban</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Mã số<span class="require">*</span></label>
                    {!!Form::text('makhoipb', null, array('id' => 'makhoipb','class' => 'form-control required'))!!}

                    <label class="form-control-label">Lĩnh vực hoạt động<span class="require">*</span></label>
                    {!!Form::text('tenkhoipb', null, array('id' => 'tenkhoipb','class' => 'form-control required'))!!}

                    <label class="form-control-label">Ghi chú</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}

                    <input type="hidden" id="id" name="id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(function(){
            $('#mucbaomat').change(function() {
                window.location.href = '/danh_muc/khoi_pb/ma_so='+$('#mucbaomat').val();
            });
        })

        function addPB(){
            $('#makhoipb').val('');
            $('#tenkhoipb').val('');
            $('#ghichu').val('');
            $('#id').val(0);
            $('#phongban-modal').modal('show');
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
                    $('#makhoipb').val(data.makhoipb);
                    $('#tenkhoipb').val(data.tenkhoipb);
                    $('#ghichu').val(data.ghichu);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#id').val(id);
            $('#phongban-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var message='';

            var makhoipb=$('#makhoipb').val();
            var tenkhoipb=$('#tenkhoipb').val();
            var ghichu=$('#ghichu').val();
            var id=$('#id').val();

            if(makhoipb==''){
                valid=false;
                message +='Mã số không được bỏ trống \n';
            }

            if(tenkhoipb==''){
                valid=false;
                message +='Tên khối phòng ban không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            level: $('#level').val(),
                            makhoipb: makhoipb,
                            tenkhoipb: tenkhoipb,
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            alert(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            makhoipb: makhoipb,
                            tenkhoipb: tenkhoipb,
                            ghichu: ghichu,
                            id: id
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
                alert(message);
            }

            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop