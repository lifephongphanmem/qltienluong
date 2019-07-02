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
                        <b>THÔNG TIN HỆ SỐ BẢO HIỂM PHẢI NỘP </b>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Tổng hợp</br> và dự toán</th>
                                <th class="text-center">BHXH</br>cá nhân</br>đóng</th>
                                <th class="text-center">BHYT</br>cá nhân</br>đóng</th>
                                <th class="text-center">KPCĐ</br>cá nhân</br>đóng</th>
                                <th class="text-center">BHTN</br>cá nhân</br>đóng</th>

                                <th class="text-center">BHXH</br>đơn vị</br>đóng</th>
                                <th class="text-center">BHYT</br>đơn vị</br>đóng</th>
                                <th class="text-center">KPCĐ</br>đơn vị</br>đóng</th>
                                <th class="text-center">BHTN</br>đơn vị</br>đóng</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencongtac}}</td>
                                        <td class="text-center">{{$value->tonghop == 1 ? 'Tổng hợp và dự toán':''}}</td>
                                        <td class="text-center">{{$value->bhxh}}</td>
                                        <td class="text-center">{{$value->bhyt}}</td>
                                        <td class="text-center">{{$value->kpcd}}</td>
                                        <td class="text-center">{{$value->bhtn}}</td>

                                        <td class="text-center">{{$value->bhxh_dv}}</td>
                                        <td class="text-center">{{$value->bhyt_dv}}</td>
                                        <td class="text-center">{{$value->kpcd_dv}}</td>
                                        <td class="text-center">{{$value->bhtn_dv}}</td>
                                        <td>
                                            <button type="button" onclick="editCV('{{$value->mact}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            <button type="button" onclick="updateCV('{{$value->mact}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-share-square-o"></i>&nbsp; Cập nhật</button>
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

    <!--Modal thông tin-->
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin phân loại công tác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Phần trăm bảo hiểm cá nhân nộp
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH xã hội </label>
                                                {!!Form::text('bhxh', 0, array('id' => 'bhxh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt', 0, array('id' => 'bhyt','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn', 0, array('id' => 'bhtn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd', 0, array('id' => 'kpcd','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>

                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Phần trăm bảo hiểm đơn vị nộp
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH xã hội </label>
                                                {!!Form::text('bhxh_dv', 0, array('id' => 'bhxh_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH y tế </label>
                                                {!!Form::text('bhyt_dv', 0, array('id' => 'bhyt_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">BH thất nghiệp </label>
                                                {!!Form::text('bhtn_dv', 0, array('id' => 'bhtn_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">KP công đoàn </label>
                                                {!!Form::text('kpcd_dv', 0, array('id' => 'kpcd_dv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>

                        <div class="col-md-12">
                            <label class="form-control-label">Ghi chú</label>
                            {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id"/>
                    <input type="hidden" id="mact" name="mact"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal cập nhật -->
    <div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'capnhat_bh','id' => 'frm_chuyen','method'=>'GET'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý cập nhật?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Tỷ lệ bảo hiểm của các cán bộ ứng với phân loại công tác này sẽ được cập nhật lại.</b></label>
                    </div>
                    <input type="hidden" name="mact_bh" id="mact_bh">
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>

                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <script>
        function updateCV(mact){
            $('#mact_bh').val(mact);
            $('#update-modal').modal('show');
        }

        function editCV(mact){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_bh',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mact: mact,
                    madv: '{{session('admin')->madv}}'
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mact').val(data.mact);
                    $('#ghichu').val(data.ghichu);
                    $('#bhxh').val(data.bhxh);
                    $('#bhyt').val(data.bhyt);
                    $('#kpcd').val(data.kpcd);
                    $('#bhtn').val(data.bhtn);
                    $('#bhxh_dv').val(data.bhxh_dv);
                    $('#bhyt_dv').val(data.bhyt_dv);
                    $('#kpcd_dv').val(data.kpcd_dv);
                    $('#bhtn_dv').val(data.bhtn_dv);

                    $('#id').val(data.id);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var mact = $('#mact').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl}}' + 'update_bh',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mact: mact,
                    madv: '{{session('admin')->madv}}',
                    bhxh: $('#bhxh').val(),
                    bhyt: $('#bhyt').val(),
                    kpcd: $('#kpcd').val(),
                    bhtn: $('#bhtn').val(),
                    bhxh_dv: $('#bhxh_dv').val(),
                    bhyt_dv: $('#bhyt_dv').val(),
                    kpcd_dv: $('#kpcd_dv').val(),
                    bhtn_dv: $('#bhtn_dv').val()
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
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop