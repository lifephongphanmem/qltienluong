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
                        <i class="fa fa-list-alt"></i>DANH SÁCH NGUỒN KINH PHÍ CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <a href="{{url($furl.'create')}}" class="btn btn-default btn-xs"> Thêm mới hồ sơ</a>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân</br>sách</th>
                                <th class="text-center">Nguồn kinh phí</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr class="{{getTextStatus($value->trangthai)}}">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->namns}}</td>
                                        <td class="text-right">{{number_format($value->tongcong)}}</td>
                                        <td>{{$a_trangthai[$value->trangthai]}}</td>
                                        <td>
                                            @if($value->trangthai != 'DAGUI')
                                                <a href="{{url($furl.'ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>

                                                <button type="button" class="btn btn-default btn-xs mbs" onclick="confirmChuyen('{{$value['masodv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>

                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                            @if($value->trangthai == 'DAGUI')
                                                <a href="{{url($furl.'ma_so='.$value->masodv.'/show')}}" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                            @endif
                                            <!--button type="button" onclick="indutoan('{{$value->namns}}','{{$value->masodv}}')" class="btn btn-default btn-xs mbs" data-target="#indt-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; In số liệu</button-->
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

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In nhu cầu kinh phí</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'printf?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp nhu cầu và nguồn thực hiện (Mẫu 4b)</button>
                        </div>
                    </div>

                    <!--div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'printf_m2?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; In dự toán tổng hợp - mẫu 02</button>
                        </div>
                    </div-->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal" data-toggle="modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'mautt107_m2?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (Tổng hợp chi lương và nâng lương)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'mautt107_m2?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (Tổng hợp chi lương các tháng)</button>
                        </div>
                    </div-->

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="innangluong()" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nâng lương</button>
                        </div>
                    </div>

                    <!--div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="innghihuu()" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nghỉ hưu</button>
                        </div>
                    </div-->
                </div>
                <input type="hidden" id="nam_dt" name="nam_dt"/>
                <input type="hidden" id="masodv_dt" name="masodv_dt"/>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>


    <!--Modal thêm mới -->
    {!! Form::open(['url'=>$furl.'create','method'=>'POST', 'id' => 'create_dutoan', 'class'=>'horizontal-form']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin nguồn kinh phí</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Lĩnh vực hoạt động</label>
                            {!!Form::select('linhvuchoatdong',getLinhVucHoatDong(false), session('admin')->maphanloai == 'KVXP'?'QLNN':null, array('id' => 'linhvuchoatdong','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Căn cứ thông tư, quyết định</label>
                            {!!Form::select('sohieu',getThongTuQD(), null, array('id' => 'sohieu','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Mức lương định mức</label>
                            {!!Form::text('muccu', null, array('id' => 'muccu','class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Mức lương áp dụng</label>
                            {!!Form::text('mucapdung', null, array('id' => 'mucapdung','class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Mức chênh lệch</label>
                            {!!Form::text('chenhlech', null, array('id' => 'chenhlech','class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Nội dung</label>
                            {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" checked id="nghihuu" name="nghihuu" />
                            <label for="nghihuu">Tính dự toán cho cán bộ nghỉ hưu</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" id="thaisan" name="thaisan" />
                            <label for="thaisan">Tính thời gian nghỉ thai sản của cán bộ</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="confirm_create()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'senddata','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi gửi.</b></label>
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
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
        function indutoan(namdt, masodv){
            $('#nam_dt').val(namdt);
            $('#masodv_dt').val(masodv);
        }

        function intonghopdt(url) {
            var masodv = $('#masodv_dt').val();
            window.open(url + masodv,'_blank');
        }

        function innangluong() {
            var masodv = $('#masodv_dt').val();
            window.open('{{$furl}}'+'nangluong?maso='+ masodv,'_blank');
        }

        $(function(){
            $("#sohieu").change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/nguon_kinh_phi/get_thongtu',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        sohieu: $("#sohieu").val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $("#muccu").val(data.muccu);
                        $("#mucapdung").val(data.mucapdung);
                        $("#chenhlech").val(data.chenhlech);
                    }
                });
            });

            $('#create_dutoan :submit').click(function(){
                var str = '';
                var ok = true;

                if(!$('#sohieu').val()){
                    str += '  - Số hiệu thông tư, quyết định \n';
                    $('#sohieu').parent().addClass('has-error');
                    ok = false;
                }

                //Kết quả
                if ( ok == false){
                    alert('Các trường: \n' + str + 'Không được để trống');
                    $("form").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("form").unbind('submit').submit();
                }
            });
        });

        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        function add(){
            $("#sohieu").val('').trigger('change');
            $('#create-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')
    @include('manage.nguonkinhphi.modal_printf')
@stop