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
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <label style="font-weight: bold">Số hiệu thông tư</label>
                                {!! Form::select('sohieu',$a_thongtu ,$inputs['sohieu'],array('id' => 'sohieu', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Lương tháng từ</th>
                                <th class="text-center">Lương tháng đến</th>
                                <th class="text-center">Thuế suất(%)</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-right">{{dinhdangso($value->muctu)}}</td>
                                    <td class="text-right">{{dinhdangso($value->mucden)}}</td>
                                    <td class="text-right">{{dinhdangso($value->phantram)}}</td>
                                    <td>
                                        @if(can('dmttqd','edit'))
                                            <button type="button" onclick="editCV('{{$value->id}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                        @endif

                                        @if(can('dmttqd','delete'))
                                            <button type="button" onclick="cfDel('{{$furl.'del_detail/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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
    {!! Form::open(['url'=>$furl.'store_detail', 'method' => 'POST', 'id' => 'frm_create', 'class'=>'horizontal-form form-validate']) !!}
    <div id="chucvu-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin thông tư, quyết định</h4>
                </div>
                <input type="hidden" name="sohieu" value="{{$inputs['sohieu']}}">
                <input type="hidden" name="id" />
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Lương tháng từ</label>
                                {!!Form::text('muctu', 0, array('id' => 'muctu','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Lương tháng đến</label>
                                {!!Form::text('mucden', 0, array('id' => 'mucden','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Thuế suất</label>
                                {!!Form::text('phantram', 0, array('id' => 'phantram','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
            $('#frm_create').find("[name='id']").val();
            $('#chucvu-modal').modal('show');
        }

        function editCV(id){
            $('#sohieu').prop('readonly',true);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_detail',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#frm_create').find("[name='id']").val(id);
                    $('#muctu').val(data.muctu);
                    $('#mucden').val(data.mucden);
                    $('#phantram').val(data.phantram);
                    //$('#ttdv').val(data.ttdv).trigger('change');
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#chucvu-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')
@stop