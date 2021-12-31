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
                        @if($inputs['trangthai'])
                            <a href="{{url($furl.'create?nam='.$inputs['namct'])}}" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp;Thêm mới</a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label text-right col-md-offset-2 col-md-2"> Năm chỉ tiêu</label>
                            <div class="col-md-3">
                                {!! Form::select('namct', getNam(), $inputs['namct'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm được<br>giao</th>
                                <th class="text-center">Lĩnh vực công tác</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Số lượng<br>được giao</th>
                                <th class="text-center">Số lượng<br>tuyển thêm</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{$value->nam}}</td>
                                    <td>{{isset($m_lv[$value->linhvuchoatdong])? $m_lv[$value->linhvuchoatdong] : ''}}</td>
                                    <td>{{$value->tenct}}</td>
                                    <td class="text-center">{{$value->soluongduocgiao}}</td>
                                    <td class="text-center">{{$value->soluongtuyenthem}}</td>
                                    <td>
                                        @if($inputs['trangthai'])
                                            <a href="{{url($furl.'create?id='.$value->id)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp;Sửa</a>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
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
                            <div class="col-md-12">
                                <label class="control-label">Năm được giao</label>
                                {!!Form::select('nam', getNam(), date('Y') + 1, array('id' => 'nam','class' => 'form-control text-right'))!!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Lĩnh vực công tác </label>
                                {!! Form::select('linhvuchoatdong',$m_lv,session('admin')->maphanloai == 'KVXP' ?'QLNN':null ,array('id' => 'linhvuchoatdong','class' => 'form-control select2me')) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <label class="control-label">Biên chế được giao</label>
                                {!!Form::text('soluongduocgiao', null, array('id' => 'soluongduocgiao','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        {{--<div class="row">--}}
                            {{--<div class="col-md-6">--}}
                            {{--<label class="control-label">Cán bộ không chuyên trách</label>--}}
                            {{--{!!Form::text('soluongkhongchuyentrach', null, array('id' => 'soluongkhongchuyentrach','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}--}}
                            {{--</div>--}}
                            {{--<div class="col-md-6">--}}
                                {{--<label class="control-label">Cán bộ cấp ủy viên</label>--}}
                                {{--{!!Form::text('soluonguyvien', null, array('id' => 'soluonguyvien','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}--}}
                            {{--</div>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<label class="control-label">Cán bộ đại biểu HĐND</label>--}}
                                {{--{!!Form::text('soluongdaibieuhdnd', null, array('id' => 'soluongdaibieuhdnd','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}--}}
                            {{--</div>--}}
                        {{--</div>--}}
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
//            $('#soluongkhongchuyentrach').val(0);
//            $('#soluonguyvien').val(0);
//            $('#soluongdaibieuhdnd').val(0);
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
                    $('#mact').val(data.mact).trigger('change');
                    $('#linhvuchoatdong').val(data.linhvuchoatdong).trigger('change');
//                    $('#soluongkhongchuyentrach').val(data.soluongkhongchuyentrach);
//                    $('#soluonguyvien').val(data.soluonguyvien);
//                    $('#soluongdaibieuhdnd').val(data.soluongdaibieuhdnd);
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
                    linhvuchoatdong: $('#linhvuchoatdong').val(),
                    nam:$('#nam').val(),
                    soluongduocgiao:$('#soluongduocgiao').val()
//                    soluongkhongchuyentrach:$('#soluongkhongchuyentrach').val(),
//                    soluonguyvien:$('#soluonguyvien').val(),
//                    soluongdaibieuhdnd:$('#soluongdaibieuhdnd').val()
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
        $(function(){
            $('#namct').change(function(){
                window.location.href = '/nghiep_vu/chi_tieu/danh_sach?namct='+$('#namct').val() ;
            });
        });
    </script>

    @include('includes.modal.delete')
@stop