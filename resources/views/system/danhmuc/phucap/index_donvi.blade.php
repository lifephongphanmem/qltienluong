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
                        DANH SÁCH CÁC LOẠI PHỤ CẤP
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-default btn-sm"  data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                            Khôi phục mặc định</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Mã số</th>
                                <th class="text-center">Phụ cấp</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Bao gồm các</br>loại hệ số</th>
                                <th class="text-center">Tổng hợp</br>số liệu</th>
                                <th class="text-center">Nộp</br>bảo hiểm</th>
                                <th class="text-center">Trừ</br>nghỉ phép</th>
                                <th class="text-center">Tính</br>thai sản</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$value->stt}}</td>
                                    <td>{{$value->mapc}}</td>
                                    <td>{{$value->tenpc}}</td>
                                    <td>{{$value->tenphanloai}}</td>
                                    <td>{{$value->tencongthuc}}</td>
                                    <td class="text-center">{{$value->tonghop == 1 ? 'Tổng hợp và dự toán':''}}</td>
                                    <td class="text-center">{{$value->baohiem == 1 ? 'Nộp bảo hiểm':''}}</td>
                                    <td class="text-center">{{$value->nghiom == 1 ? 'Trừ nghỉ phép':''}}</td>
                                    <td class="text-center">{{$value->thaisan == 1 ? 'Tính thai sản':''}}</td>
                                    <td>
                                        <a href="{{$furl.'edit?maso='.$value->mapc}}" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp; Sửa</a>

                                        <a href="{{$furl.'anhien?id='.$value->id}}" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp; {{$value->phanloai == 3? 'Hiện' : 'Ẩn'}}</a>
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

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'default_pc','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý khôi phục thiết lập mặc định?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Các thiết đặt về phụ cấp của bạn sẽ thiết đặt lại. Bạn có chắc chắn muốn khôi khục?</b></label>
                    </div>
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
        $(function() {
            //Multi select box
            //$("#ctpc").select2();
            $("#ctpc").change(function () {
                $("#congthuc").val($("#ctpc").val());
            });
        });

        function add() {
            $('#ctpc').val('').trigger('change');
            $('#congthuc').val('');
            $('#mapc').val('');
            $('#chitiet-modal').modal('show');
        }

        function getInfo() {
            window.location.href = '{{$furl}}' + 'maso=' + $('#cbmacb').val();
        }

        function edit(mapc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mapc: mapc
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#ngaytu').val(data.ngaytu);
                    $('#ngayden').val(data.ngayden);
                    $('#mapc').val(data.mapc);
                    $('#hesopc').val(data.hesopc);
                },
                error: function(message){
                    alert(message);
                }
            });

            $('#id_ct').val(id);
            $('#chitiet-modal').modal('show');
        }

        function confirm(){
            var valid=true;
            var message='';

            var mapc=$('#mapc').val();
            var tenpc=$('#tenpc').val();


            if(mapc=='' || tenpc==''){
                valid=false;
                message +='Mã số và tên phụ cấp không được bỏ trống \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$furl}}' + 'store',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        mapc: mapc,
                        tenpc: tenpc,
                        phanloai: $('#phanloai').val(),
                        form: $('#form').val(),
                        report: $('#report').val(),
                        congthuc: $('#congthuc').val()
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


                $('#chitiet-modal').modal('hide');
            }else{
                alert(message);
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop