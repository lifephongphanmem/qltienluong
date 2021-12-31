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
    @include('includes.script.scripts')
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
                        <b>ĐỊNH MỨC NGUỒN KINH PHÍ TẠI ĐƠN VỊ</b>
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Mã</br>nguồn</th>
                                <th class="text-center">Nguồn kinh phí</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Mức lương</br>cơ bản</th>
                                <th class="text-center">Nộp</br>bảo hiểm</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{$value->manguonkp}}</td>
                                    <td>{{$value->tennguonkp}}</td>
                                    <td>{{$value->tenct}}</td>
                                    <td class="text-right">{{dinhdangso($value->luongcoban)}}</td>
                                    <td class="text-center">{{$value->baohiem == 1 ? 'Nộp bảo hiểm': ''}}</td>
                                    <td>
                                        <button type="button" onclick="editCV('{{$value->maso}}')" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp;Sửa</button>
                                        <a href="{{url($furl.'phu_cap?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                            <i class="fa fa-list"></i>&nbsp;Phụ cấp</a>
                                        <button type="button" onclick="cfDel('{{$value->maso}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                            <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin -->
    <div id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['id'=>'frm_edit', 'method'=>'POST','url'=>$furl.'store']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin định mức nguồn kinh phí</h4>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-control-label">Nguồn kinh phí</label>
                                {!!Form::select('manguonkp', getNguonKP(false), null, array('id' => 'manguonkp','class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Phân loại công tác</label>
                                <select class="form-control select2me" name="mact[]" id="mact" multiple required="required">
                                    <option value="ALL">Tất cả phân loại công tác</option>
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
                                <label class="control-label">Mức lương cơ bản</label>
                                {!!Form::text('luongcoban', null, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Nộp bảo hiểm</label>
                                {!!Form::select('baohiem', array('0'=>'Không nộp bảo hiểm','1'=>'Có nộp bảo hiểm'), null, array('id' => 'baohiem','class' => 'form-control'))!!}
                            </div>
                        </div>
                        <input type="hidden" id="maso" name="maso"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfCV()">Đồng ý</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="delete-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>$furl.'destroy','method'=>'post' , 'files'=>true, 'id' => 'frmDelete']) !!}
        <input type="hidden" id="maso_del" name="maso_del"/>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        function add(){
            $('#maso').val('ADD');
            $('#manguonkp').prop('disabled',false);
            $('#luongcoban').val('{{session('admin')->luongcoban}}');
            $('#mact').select2("val",[]);
            $('#edit-modal').modal('show');
        }

        function editCV(maso){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    maso: maso
                },
                dataType: 'JSON',
                success: function (data) {
                    var a_ct = data.mact.split(',');
                    $('#manguonkp').val(data.manguonkp);
                    $('#tennguonkp').val(data.tennguonkp);
                    $('#baohiem').val(data.baohiem);
                    $('#luongcoban').val(data.luongcoban);
                    $('#maso').val(maso);
                    $('#mact').select2("val",a_ct);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#manguonkp').prop('disabled',true);
            $('#edit-modal').modal('show');
        }

        function cfDel(maso){
            $('#maso_del').val(maso);
        }

        {{--function cfCV(){--}}
            {{--var maso=$('#maso').val();--}}
            {{--var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}
            {{--$.ajax({--}}
                {{--url: '{{$furl}}' + 'update',--}}
                {{--type: 'GET',--}}
                {{--data: {--}}
                    {{--_token: CSRF_TOKEN,--}}
                    {{--maso: maso,--}}
                    {{--baohiem: $('#baohiem').val(),--}}
                    {{--luongcoban: $('#luongcoban').val()--}}
                {{--},--}}
                {{--dataType: 'JSON',--}}
                {{--success: function (data) {--}}
                    {{--if (data.status == 'success') {--}}
                        {{--location.reload();--}}
                    {{--}--}}
                {{--},--}}
                {{--error: function(message){--}}
                    {{--toastr.error(message,'Lỗi!!');--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}
    </script>
@stop