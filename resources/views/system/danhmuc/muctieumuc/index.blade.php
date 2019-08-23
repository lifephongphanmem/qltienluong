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
            $("#mapc").select2();
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
                    @if(can('congthucmtm','create'))
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
                                <th class="text-center">Tiểu mục</th>
                                <th class="text-center">Nội dung</th>
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
                                        <td>{{$value->tenct}}</td>
                                        <td>{{$value->tenpc}}</td>
                                        <td>
                                            @if(can('congthucmtm','edit'))
                                                <button type="button" onclick="editCV('{{$value->tieumuc}}')" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif

                                            @if(can('congthucmtm','delete'))
                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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
        {!! Form::open(['id'=>'frm_add', 'method'=>'POST','url'=>$furl.'store']) !!}
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Phân loại công tác</label>
                                <select class="form-control select2me" name="mact[]" id="mact" multiple required="required">
                                    <option value="null">Không chọn</option>
                                    <option value="ALL">Tất cả</option>
                                    @foreach($model_nhomct as $kieuct)
                                        <optgroup label="{{$kieuct->tencongtac}}">
                                            <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                            @foreach($mode_ct as $ct)
                                                <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Phụ cấp</label>
                                {!! Form::select('mapc[]',$a_pc,null,array('id' => 'mapc','class' => 'form-control select2me','multiple'=>'multiple','required'=>'required')) !!}
                            </div>
                        </div>
                    </div>
                    <!--input type="hidden" id="id" name="id"/-->
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
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
                    var a_pc = data.mapc.split(',');
                    var a_ct = data.mact.split(',');
                    $('#muc').val(data.muc);
                    $('#tieumuc').val(data.tieumuc);
                    $('#noidung').val(data.noidung);
                    $('#mact').select2("val",a_ct);
                    $('#mapc').select2("val",a_pc);
                    //$('#macongtac').val(data.macongtac).trigger('change');
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');

        }


    </script>

    @include('includes.modal.delete')
@stop