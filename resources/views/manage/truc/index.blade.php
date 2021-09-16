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
                        DANH SÁCH CÁN BỘ HƯỞNG PHỤ CẤP THEO NGÀY LÀM VIỆC
                    </div>
                    @if($inputs['trangthai'])
                        <div class="actions">
                            <button type="button" class="btn btn-default btn-xs" onclick="saochep()"><i class="fa fa-plus"></i>&nbsp;Sao chép</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        </div>
                    @endif
                </div>

                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-2 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thangct',getThang(),$inputs['thang'],array('id' => 'thangct', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('namct',getNam(),$inputs['nam'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 5%">STT</th>
                                <th class="text-center" rowspan="2" >Họ tên cán bộ</th>
                                <th class="text-center" rowspan="2" >Số</br>ngày</br>công</th>
                                <th class="text-center" colspan="6">Danh sách phụ cấp</th>
                                <th class="text-center" rowspan="2" >Thao tác</th>
                            </tr>
                            <tr>
                                <th style="width: 8%" class="text-center">Ưu</br>đãi</th>
                                <th style="width: 8%" class="text-center">Ưu đãi</br>chênh</br>lệch</th>
                                <th style="width: 8%" class="text-center">Độc</br>hại</th>
                                <th style="width: 8%" class="text-center">Trách</br>nhiệm</th>
                                <th style="width: 8%" class="text-center">Lưu</br>động</th>
                                <th style="width: 8%" class="text-center">Làm</br>thêm,</br>làm</br>đêm</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td class="text-center">{{$value->songaytruc .'/'. $value->songaycong}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pcudn,5)}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pcud61,5)}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pcdh,5)}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pctn,5)}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pcld,5)}}</td>
                                    <td class="text-center">{{dinhdangsothapphan($value->pclade,5)}}</td>
                                    <td>
                                        @if($inputs['trangthai'])
                                            <a href="{{$inputs['furl'].'edit?macanbo='.$value->macanbo.'&thang='.$value->thang.'&nam='.$value->nam}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp;Sửa</a>
                                            <button type="button" onclick="cfDel('{{$inputs['furl'].'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    @include('includes.modal.delete')

    {!! Form::open(['url'=>$inputs['furl'].'store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng lương trực cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <table id="sample_5" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Họ tên cán bộ</th>
                            <th class="text-center">Chức vụ</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <?php $i=1;?>
                        <tbody>
                        @foreach($m_cb as $key=>$value)
                            <tr>
                                <td class="text-center">{{$i++}}</td>
                                <td>{{$value->tencanbo}}</td>
                                <td>{{isset($a_cv[$value->macvcq])? $a_cv[$value->macvcq] : ''}}</td>
                                <td>
                                    <a href="{{url($inputs['furl'].'create?macanbo='.$value->macanbo.'&thang='.$inputs['thang'].'&nam='.$inputs['nam'])}}" class="btn btn-default btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp;Chọn</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer"></div>
        </div>
    </div>
    {!! Form::close() !!}

    {!! Form::open(['url'=>$inputs['furl'].'copy','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="saochep-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Sao chép bảng trực cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Tháng</label>
                            {!!Form::select('thang_sao', getThang(),null, array('id' => 'thang_sao','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Năm</label>
                            {!!Form::select('nam_sao', getNam(),date('Y'), array('id' => 'nam_sao','class' => 'form-control'))!!}
                        </div>
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <label class="control-label">Tổng số ngày công</label>--}}
{{--                            {!!Form::text('ngaycong_sao', session('admin')->songaycong, array('id' => 'ngaycong_sao','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <input type="hidden" name="thang" id="thang" value="{{$inputs['thang']}}" >
            <input type="hidden" name="nam" id="nam" value="{{$inputs['nam']}}" >
            <div class="modal-footer">
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <script>
        function add(){
            $('#id').val('ADD');
            $('#chitiet-modal').modal('show');
        }

        function saochep(){
            $('#saochep-modal').modal('show');
        }

        function getLink(){
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{$inputs['furl']}}'+'danh_sach?thang='+thang +'&nam='+nam;
        }

        $('#thangct').change(function(){
            window.location.href = getLink();
        });

        $('#namct').change(function(){
            window.location.href = getLink();
        });

        function edit(id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var kq = $.ajax({
                url: '{{$inputs['furl']}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#heso').val(data.heso);
                    $('#tencanbo').val(data.tencanbo);
                    $('#id').val(data.id);
                    $('#chitiet-modal').modal('show');
                },
                error: function (message) {
                    alert('Lỗi: '+ message);
                }
            });

        }

        function confirm(){
            var valid=true;
            var message='';

            var macanbo = $('#cbmacb').val();

            var msngbac=$('#msngbac').val();
            var ngaytu=$('#ngaytu').val();
            var ngayden=$('#ngayden').val();
            var bac=$('#bac').val();
            var heso=$('#heso').val();
            var vuotkhung=$('#vuotkhung').val();
            var pthuong=$('#pthuong').val();

            var id=$('#id_ct').val();

            if(ngaytu==''){
                valid=false;
                message +='Ngày hưởng lương không được bỏ trống \n';
            }
            if(msngbac==''){
                valid=false;
                message +='Mã ngạch lương không được bỏ trống \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$inputs['furl_ajax']}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            macanbo: macanbo,
                            ngaytu: ngaytu,
                            ngayden: ngayden,
                            msngbac: msngbac,
                            bac: bac,
                            heso: heso,
                            vuotkhung: vuotkhung,
                            pthuong: pthuong
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
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$inputs['furl_ajax']}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            ngaytu: ngaytu,
                            ngayden: ngayden,
                            msngbac: msngbac,
                            bac: bac,
                            heso: heso,
                            vuotkhung: vuotkhung,
                            pthuong: pthuong,
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
    @include('includes.script.scripts')
@stop