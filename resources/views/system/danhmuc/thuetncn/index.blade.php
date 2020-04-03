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
                        <b>DANH MỤC THÔNG TƯ, QUYẾT ĐỊNH HƯỚNG DẪN TÍNH THUẾ THU NHẬP CÁ NHÂN</b>
                    </div>
                    @if(can('dmttqd','create'))
                        <div class="actions">
                            <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="addCV()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        </div>
                    @endif
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Số hiệu</th>
                                <th class="text-center">Tên thông tư,</br>quyết định</th>
                                <th class="text-center">Ngày áp dụng</th>
                                <th class="text-center">Giảm trừ</br>bản thân</th>
                                <th class="text-center">Giảm trừ</br>người phụ</br>thuộc</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->sohieu}}</td>
                                    <td>{{$value->tenttqd}}</td>
                                    <td>{{getDayVn($value->ngayapdung)}}</td>
                                    <td class="text-right">{{dinhdangso($value->banthan)}}</td>
                                    <td class="text-right">{{dinhdangso($value->phuthuoc)}}</td>
                                    <td>
                                        <a href="{{url($furl.'detail?sohieu='.$value->sohieu)}}" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>

                                        @if(can('dmttqd','edit'))
                                            <button type="button" onclick="editCV('{{$value->sohieu}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                        @endif

                                        @if(can('dmttqd','delete'))
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chức vụ -->
    {!! Form::open(['url'=>$furl.'store', 'method' => 'POST', 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
    <div id="chucvu-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin thông tư, quyết định</h4>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-control-label">Số hiệu<span class="require">*</span></label>
                                {!!Form::text('sohieu', null, array('id' => 'sohieu','class' => 'form-control', 'required'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="form-control-label">Tên thông tư, quyết định</label>
                                {!!Form::text('tenttqd', null, array('id' => 'tenttqd','class' => 'form-control'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="form-control-label">Thời gian áp dụng</label>
                                <input type="date" name="ngayapdung" id="ngayapdung" class="form-control" required />

                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Mức giảm trừ bản thân</label>
                                {!!Form::text('banthan', 0, array('id' => 'banthan','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Mức giảm trừ người phụ thuộc</label>
                                {!!Form::text('phuthuoc', 0, array('id' => 'phuthuoc','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function addCV(){
            $('#id_tt').val('ADD');
            $('#sohieu').val('');
            $('#sohieu').prop('readonly',false);
            $('#chucvu-modal').modal('show');
        }

        function editCV(sohieu){
            $('#sohieu').prop('readonly',true);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    sohieu: sohieu
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#sohieu').val(data.sohieu);
                    $('#tenttqd').val(data.tenttqd);
                    $('#ngayapdung').val(data.ngayapdung);
                    $('#banthan').val(data.banthan);
                    $('#phuthuoc').val(data.phuthuoc);
                    //$('#ttdv').val(data.ttdv).trigger('change');
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#chucvu-modal').modal('show');
        }

        function cfCV(){
            var valid=true;
            var message='';
            var sohieu=$('#sohieu').val();
            var id=$('#id_tt').val();

            if(sohieu==''){
                valid=false;
                message += 'Số hiệu không được bỏ trống. \n';
            }
            if($('#ngayapdung').val()==''){
                valid=false;
                message += ' Thời gian áp dụng không được bỏ trống. \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$furl}}' + 'store',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        sohieu: sohieu,
                        tenttqd: $('#tenttqd').val(),
                        ngayapdung: $('#ngayapdung').val(),
                        banthan: $('#banthan').val(),
                        phuthuoc: $('#phuthuoc').val(),
                        id: id
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
                //$('#chucvu-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!.');
            }
        }
    </script>

    @include('includes.modal.delete')
@stop