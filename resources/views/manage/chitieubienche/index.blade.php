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
                        <i class="fa fa-list-alt"></i>DANH SÁCH CHỈ TIÊU BIÊN CHẾ CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm</br>được</br>giao</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Số lượng</br>biên chế</br>được giao</th>
                                <th class="text-center">Số lượng</br>biên chế</br>hiện có</th>
                                <th class="text-center">Số lượng</br>cán bộ không</br>chuyên trách</br>(nếu có)</th>
                                <th class="text-center">Số lượng</br>đại biểu</br>HĐND</th>
                                <th class="text-center">Số lượng</br>ủy viên</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr class="text-center">
                                        <td>{{$key+1}}</td>
                                        <td>{{$value->nam}}</td>
                                        <td class="text-left">{{$value->tenct}}</td>
                                        <td>{{$value->soluongduocgiao}}</td>
                                        <td>{{$value->soluongbienche}}</td>
                                        <td>{{$value->soluongkhongchuyentrach}}</td>
                                        <td>{{$value->soluonguyvien}}</td>
                                        <td>{{$value->soluongdaibieuhdnd}}</td>
                                        <td>
                                            <button type="button" onclick="edit({{$value->id}})" class="btn btn-info btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp;Sửa</button>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
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
                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label">Năm được giao</label>
                                {!!Form::select('nam', getNam(), date('Y') + 1, array('id' => 'nam','class' => 'form-control text-right'))!!}
                            </div>

                            <div class="col-md-8">
                                <label class="control-label">Phân loại công tác</label>
                                <select class="form-control select2me" name="mact" id="mact" required="required">
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

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Biên chế được giao</label>
                                {!!Form::text('soluongduocgiao', null, array('id' => 'soluongduocgiao','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Biên chế hiện có</label>
                                {!!Form::text('soluongbienche', null, array('id' => 'soluongbienche','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Cán bộ không chuyên trách</label>
                                {!!Form::text('soluongkhongchuyentrach', null, array('id' => 'soluongkhongchuyentrach','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Cán bộ cấp ủy viên</label>
                                {!!Form::text('soluonguyvien', null, array('id' => 'soluonguyvien','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Cán bộ đại biểu HĐND</label>
                                {!!Form::text('soluongdaibieuhdnd', null, array('id' => 'soluongdaibieuhdnd','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
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
            $('#nam').prop('readonly',false);
            //$('#nam').val(0);
            $('#soluongduocgiao').val(0);
            $('#soluongbienche').val(0);
            $('#soluongkhongchuyentrach').val(0);
            $('#soluonguyvien').val(0);
            $('#soluongdaibieuhdnd').val(0);
            $('#id_ct').val('ADD');
            $('#chitiet-modal').modal('show');
        }

        function edit(id){
            $('#nam').prop('readonly',true);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
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
                    $('#mact').val(data.mact).trigger('change');
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

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'store',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: $('#id_ct').val(),
                    mact: $('#mact').val(),
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
                    if (data.status == 'error') {
                        toastr.error(data.message, 'Lỗi!');
                    }

                },
                error: function(message){
                    toastr.error(message, 'Lỗi!');
                }
            });

            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop