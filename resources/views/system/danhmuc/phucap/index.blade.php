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
                    @if(can('dmphucap','create'))
                        <div class="actions">
                            <a href="{{url($furl.'create')}}" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp;Thêm mới</a>
                        </div>
                    @endif
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr class="text-center" >
                                <th style="width: 5%">STT</th>
                                <th class="text-center">Mã số</th>
                                <th class="text-center">Phụ cấp</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Bao gồm các</br>loại hệ số</th>
                                <th class="text-center">Tổng hợp</br>số liệu</th>
                                <th class="text-center">Dự toán</br>lương</th>
                                <th class="text-center">Nộp</br>bảo</br>hiểm</th>
                                <th class="text-center">Trừ</br>nghỉ</br>phép</th>
                                <th class="text-center">Hưởng</br>thai</br>sản</th>
                                <th class="text-center">Hưởng</br>điều</br>động</th>
                                <th style="width: 5%" class="text-center">Tính</br>thuế</br>thu</br>nhập</th>
                                <th style="width: 5%" class="text-center">Tính</br>tập</br>sự</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->mapc}}</td>
                                    <td>{{$value->tenpc}}</td>
                                    <td>{{$value->tenphanloai}}</td>
                                    <td>{{$value->tencongthuc}}</td>
                                    <td class="text-center">{!!$value->tonghop == 1 ? '<i class="fa fa-check"></i>':''!!} </td>
                                    <td class="text-center">{!!$value->dutoan == 1 ? '<i class="fa fa-check"></i>':''!!} </td>
                                    <td class="text-center">{!!$value->baohiem == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td class="text-center">{!!$value->nghiom == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td class="text-center">{!!$value->thaisan == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td class="text-center">{!!$value->dieudong == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td class="text-center">{!!$value->thuetn == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td class="text-center">{!!$value->tapsu == 1 ? '<i class="fa fa-check"></i>':''!!}</td>
                                    <td>
                                        @if(can('dmphucap','edit'))
                                            <a href="{{$furl.'edit?maso='.$value->mapc}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</a>
                                        @endif

                                        @if(can('dmphucap','delete'))
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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