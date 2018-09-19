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
                        <b>THÔNG TIN ĐỊNH MỨC NGUỒN KINH PHÍ TẠI ĐƠN VỊ</b>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Mã nguồn</th>
                                <th class="text-center">Tên nguồn kinh phí</th>
                                <th class="text-center">Mức lương cơ bản</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->manguonkp}}</td>
                                        <td>{{$value->tennguonkp}}</td>
                                        <td class="text-right">{{dinhdangso($value->luongcoban)}}</td>
                                        <td>
                                            <button type="button" onclick="editCV('{{$value->maso}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            <a href="{{url($furl.'phu_cap?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Phụ cấp</a>
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
    <div id="chucvu-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                                <label class="form-control-label">Mã số</label>
                                {!!Form::text('manguonkp', null, array('id' => 'manguonkp','class' => 'form-control','readonly'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="form-control-label">Tên nguồn kinh phí</label>
                                {!!Form::text('tennguonkp', null, array('id' => 'tennguonkp','class' => 'form-control','readonly'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Mức lương cơ bản</label>
                                {!!Form::text('luongcoban', null, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>

                            <input type="hidden" id="maso" name="maso"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfCV()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                    $('#manguonkp').val(data.manguonkp);
                    $('#tennguonkp').val(data.tennguonkp);
                    $('#luongcoban').val(data.luongcoban);
                    $('#maso').val(maso);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#chucvu-modal').modal('show');
        }

        function cfCV(){
            var maso=$('#maso').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'update',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    maso: maso,
                    luongcoban: $('#luongcoban').val()
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
    </script>

    @include('includes.modal.delete')
@stop