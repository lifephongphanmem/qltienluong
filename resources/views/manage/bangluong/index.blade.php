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
                    <div class="caption">DANH SÁCH BẢNG LƯƠNG - {{$tendv}}</div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="addexcel()"><i class="fa fa-plus"></i>&nbsp;Nhận từ file Excel</button>

                        <a href="{{url('mauexcel')}}" class="btn btn-default btn-xs"><i class="fa fa-download"></i> Tải file Excel mẫu</a>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tháng</th>
                                <th class="text-center">Năm</th>
                                <th class="text-center">Nội dung bảng lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->thang}}</td>
                                        <td>{{$value->nam}}</td>
                                        <td>{{$value->noidung}}</td>
                                        <td>
                                            <button type="button" onclick="edit({{$value->id}})" class="btn btn-info btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <a href="{{url($furl.'maso='.$value->mabl)}}" class="btn btn-warning btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>
                                            <a href="{{url($furl.'in/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</a>
                                            <a href="{{url($furl.'in_bh/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảo hiểm</a>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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
        <div class="modal-dialog modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Tạo bảng lương</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select(
                                'thang',
                                array(
                                '01' => '01',
                                '02' => '02',
                                '03' => '03',
                                '04' => '04',
                                '05' => '05',
                                '06' => '06',
                                '07' => '07',
                                '08' => '08',
                                '09' => '09',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                                ),null,
                                array('id' => 'thang', 'class' => 'form-control'))
                                !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select(
                                'nam',
                                array(
                                '2015' => '2015',
                                '2016' => '2016',
                                '2017' => '2017'
                                ),null,
                                array('id' => 'nam', 'class' => 'form-control'))
                                !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Nội dung</label>
                            <div class="col-md-8">
                                {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}
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

    <div id="chitiet-excel-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'/chuc_nang/bang_luong/importexcel','method'=>'post' , 'files'=>true, 'id' => 'create_hscb','enctype'=>'multipart/form-data']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Nhận bảng lương từ file excel</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'thang',
                            array(
                            '01' => '01',
                            '02' => '02',
                            '03' => '03',
                            '04' => '04',
                            '05' => '05',
                            '06' => '06',
                            '07' => '07',
                            '08' => '08',
                            '09' => '09',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            ),null,
                            array('id' => 'thang', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'nam',
                            array(
                            '2015' => '2015',
                            '2016' => '2016',
                            '2017' => '2017',
                            '2018' => '2018'
                            ),null,
                            array('id' => 'nam', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Nội dung</label>
                        <div class="col-md-8">
                            {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> File Excel bảng lương<span class="require">*</span></label>
                        <div class="col-md-8">
                            <input name="fexcel" type="file" class="required" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Sheet lấy dữ liệu</label>
                        <div class="col-md-8">
                            {!!Form::text('sheet', '1', array('id' => 'sheet','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ dòng</label>
                        <div class="col-md-8">
                            {!!Form::text('tudong', '10', array('id' => 'tudong','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến dòng</label>
                        <div class="col-md-8">
                            {!!Form::text('sodong', '100', array('id' => 'sodong','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <input type="hidden" id="id_ct" name="id_ct"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" >Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script>
        function add(){
            $('#thang').val('');
            $('#nam').val('');
            $('#noidung').val('');
            $('#id_ct').val(0);
            $('#chitiet-modal').modal('show');
        }

        function addexcel(){
            $('#thang').val('');
            $('#nam').val('');
            $('#noidung').val('');
            $('#id_ct').val(0);
            $('#chitiet-excel-modal').modal('show');
        }

        function edit(id){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_ajax}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#thang').val(data.thang);
                    $('#nam').val(data.nam);
                    $('#noidung').val(data.noidung);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#id_ct').val(id);
            $('#chitiet-modal').modal('show');
        }

        function confirm(){
            var valid=true;
            var message='';

            var thang=$('#thang').val();
            var nam=$('#nam').val();
            var noidung=$('#noidung').val();

            var id=$('#id_ct').val();

            if(thang==''){
                valid=false;
                message +='Tháng bảng lương không được bỏ trống \n';
            }
            if(nam==''){
                valid=false;
                message +='Năm bảng lương không được bỏ trống \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            thang: thang,
                            nam: nam,
                            noidung: noidung
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                window.location.href = data.message;
                            }
                        },
                        error: function(message){
                            alert(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl_ajax}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            thang: thang,
                            nam: nam,
                            noidung: noidung,
                            id: id
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
                }
                $('#chitiet-modal').modal('hide');
            }else{
                alert(message);
            }
            return valid;
        }

    </script>
    @include('includes.modal.delete')
@stop