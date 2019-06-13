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
                        <i class="fa fa-list-alt"></i>DANH SÁCH DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới dự toán</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân</br>sách</th>
                                <th class="text-center">Tổng số</th>
                                <th class="text-center">Lương theo</br>ngạch bậc</th>
                                <th class="text-center">Tổng các khoản</br>phụ cấp</th>
                                <th class="text-center">Các khoản</br>đóng góp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td  class="text-center">{{$value->namns}}</td>
                                        <td class="text-right">{{number_format($value->luongnb_dt + $value->luonghs_dt + $value->luongbh_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luongnb_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luonghs_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luongbh_dt)}}</td>
                                        <td>
                                            <a href="{{url($furl.'?maso='.$value->masodv)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>
                                            <!--a href="{{url($furl.'printf/ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In dự toán</a>
                                            <a href="{{url($furl.'printf_bl/ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</a-->

                                            <button type="button" onclick="indutoan('{{$value->namns}}','{{$value->masodv}}')" class="btn btn-default btn-xs mbs" data-target="#indt-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; In dự toán</button>

                                            @if($value->trangthai == 'CHUAGUI' || $value->trangthai == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-xs" onclick="confirmChuyen('{{$value->masodv}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>

                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif

                                            @if($value->trangthai == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-xs" onclick="getLyDo('{{$value['masodv']}}')" data-target="#tralai-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Lý do trả lại</button>
                                            @endif
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
                <h4 id="hd-inbl" class="modal-title">In dự toán lương</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'printf?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; In dự toán tổng hợp - mẫu 01</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{$furl.'printf_m2?maso='}}')" style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; In dự toán tổng hợp - mẫu 02</button>
                        </div>
                    </div>
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
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC) - tổng hợp</button>
                        </div>
                    </div>
                    <!--div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal" data-toggle="modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT185/2010/TT-BTC)</button>
                        </div>
                    </div-->
                </div>

                <div class="row">
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
    {!! Form::open(['url'=>$furl.'create', 'id' => 'create_dutoan', 'class'=>'horizontal-form']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin dự toán lương</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_0" data-toggle="tab" aria-expanded="true">
                                            Thông tin chung </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                                            Tạo dự toán theo bảng lương </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Năm được giao dự toán</label>
                                                {!!Form::text('namdt', date('Y') + 1, array('id' => 'namdt','class' => 'form-control'))!!}
                                            </div>

                                            <div class="col-md-6">
                                                <label class="control-label">Mức lương cơ bản</label>
                                                {!!Form::text('luongcoban', getGeneralConfigs()['luongcb'], array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>

                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                                                    <input type="checkbox" checked id="nghihuu" name="nghihuu" />
                                                    <label>Không tính dự toán cho cán bộ nghỉ hưu</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_1">
                                        <table id="sample_4" class="table table-hover table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%">STT</th>
                                                <th class="text-center">Tháng</br>Năm</th>
                                                <th class="text-center">Nguồn kinh phí</th>
                                                <th class="text-center">Nội dung</th>
                                                <th class="text-center">Thao tác</th>
                                            </tr>
                                            </thead>
                                            <?php $i=1;?>
                                            <tbody>
                                            @foreach($model_bl as $key=>$value)
                                                <tr>
                                                    <td class="text-center">{{$i++}}</td>
                                                    <td class="text-center">{{$value->thang.'/'.$value->nam}}</td>
                                                    <td>{{isset($a_nkp[$value->manguonkp]) ? $a_nkp[$value->manguonkp] : ''}}</td>
                                                    <td>{{$value->noidung}}</td>
                                                    <td>
                                                        <button type="button" onclick="taobl('{{$value->mabl}}')" class="btn btn-default btn-xs mbs">
                                                            <i class="fa fa-edit"></i>&nbsp; Chọn</button>

                                                        <a href="{{url($furl.'bang_luong?mabl='.$value->mabl.'&mapb=')}}" class="btn btn-default btn-xs mbs" target="_blank">
                                                            <i class="fa fa-th-list"></i>&nbsp; Danh sách</a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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

    <!--Model Trả lại -->
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin lý do trả lại dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>

                    <div class="modal-footer">
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

        function add(){
            $('#create-modal').modal('show');
        }

        function confirmChuyen(masodv) {
            //document.getElementById("masodv").value = masodv;
            $('#frm_chuyen').find("[id^='masodv']").val(masodv);
        }

        function innangluong() {
            var masodv = $('#masodv_dt').val();
            window.open('{{$furl}}'+'nangluong?maso='+ masodv,'_blank');
        }

        function intonghopdt(url) {
            var masodv = $('#masodv_dt').val();
            window.open(url + masodv,'_blank');
        }

        function getLyDo(masodv){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'getlydo',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    masodv: masodv
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#lydo').val(data.lydo);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            //$('#madvbc').val(madvbc);
            //$('#phongban-modal').modal('show');
        }

        function taobl(mabl){
            //var tr = $(e).closest('tr');
            $('#create-modal').modal('hide');
            var nghihuu = document.getElementById("nghihuu").checked;
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //create_bangluong
            //var form = $('#create_bangluong');
            $.ajax({
                url: '{{$furl}}' + 'create_mau',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    namdt: $('#namdt').val(),
                    luongcoban: $('#luongcoban').val(),
                    mabl: mabl,
                    nghihuu: nghihuu
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        location.reload();
                    }else{
                        toastr.error(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message);
                }
            });

        }

        function confirm_create() {
            if ($('#namdt').val() == 0 || $('#namdt').val() == '') {
                toastr.error('Năm dự toán không được bỏ trống.', 'Lỗi!');
                $("form").submit(function (e) {
                    e.preventDefault();
                });
            } else {
                $("form").unbind('submit').submit();
            }
        }

    </script>

    @include('includes.modal.delete')
    @include('manage.dutoanluong.modal_printf')
@stop