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
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    @include('includes.script.scripts')
    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
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
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i
                                class="fa fa-plus"></i>&nbsp;Thêm mới dự toán</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%">STT</th>
                                <th>Năm</br>ngân</br>sách</th>
                                <th>Tổng số</th>
                                <th>Lương theo</br>ngạch bậc</th>
                                <th>Tổng các khoản</br>phụ cấp</th>
                                <th>Các khoản</br>đóng góp</th>
                                <th>Trạng thái</th>
                                <th>Đơn vị tiếp nhận</th>
                                <th style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr class="{{ getTextStatus($value['trangthai']) }}">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $value->namns }}</td>
                                        <td class="text-right">
                                            {{ dinhdangsothapphan($value->tongdutoan) }}
                                        </td>
                                        <td class="text-right">{{ dinhdangsothapphan($value->luongnb_dt) }}</td>
                                        <td class="text-right">{{ dinhdangsothapphan($value->luonghs_dt) }}</td>
                                        <td class="text-right">{{ dinhdangsothapphan($value->luongbh_dt) }}</td>
                                        <td class="text-center bold">{{ $a_trangthai[$value['trangthai']] }}</td>
                                        <td>{{ getTenDV($value->macqcq) }}</td>
                                        <td>

                                            <button type="button" title="In số liệu"
                                                onclick="indutoan('{{ $value->namns }}','{{ $value->masodv }}')"
                                                class="btn btn-default btn-sm mbs" data-target="#indt-modal"
                                                data-toggle="modal">
                                                <i class="fa fa-print"></i>
                                            </button>

                                            @if ($value->trangthai == 'CHUAGUI' || $value->trangthai == 'TRALAI')
                                                {{-- chức năng sửa cho cán bộ không chuyên trách --}}
                                                @if ($value->phanloai == 'DUTOAN' && session('admin')->maphanloai == 'KVXP')
                                                    <button type="button"
                                                        title="Chỉnh sửa kinh phí cán bộ không chuyên trách"
                                                        onclick="getKinhPhiKoCT('{{ $value->masodv }}', 
                                                        '{{ $value->phanloaixa }}',
                                                        '{{ $value->phanloaixa_heso }}',
                                                        '{{ $value->sothonxabiengioi }}',
                                                        '{{ $value->sothonxabiengioi_heso }}',
                                                        '{{ $value->sothonxakhokhan }}',
                                                        '{{ $value->sothonxakhokhan_heso }}',
                                                        '{{ $value->sothonxatrongdiem }}',
                                                        '{{ $value->sothonxatrongdiem_heso }}',
                                                        '{{ $value->sothonxakhac }}',
                                                        '{{ $value->sothonxakhac_heso }}',
                                                        '{{ $value->sothonxaloai1 }}',
                                                        '{{ $value->sothonxaloai1_heso }}')"
                                                        class="btn btn-default btn-sm mbs" data-target="#modal-KinhPhiKoCT"
                                                        data-toggle="modal">
                                                        <i class="fa fa-edit"></i></button>
                                                @endif

                                                <button type="button" class="btn btn-default btn-sm mbs"
                                                    title="Gửi dữ liệu" onclick="confirmChuyen('{{ $value->masodv }}')"
                                                    data-target="#chuyen-modal" data-toggle="modal">
                                                    <i class="fa fa-share-square-o"></i>
                                                </button>

                                                <button type="button" title="Xóa dữ liệu"
                                                    onclick="cfDel('{{ $furl . 'del/' . $value->id }}')"
                                                    class="btn btn-default btn-sm mbs" data-target="#delete-modal-confirm"
                                                    data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i></button>
                                            @endif

                                            @if ($value->trangthai == 'TRALAI')
                                                <button type="button" class="btn btn-default btn-sm mbs"
                                                    title="Lý do trả lại" onclick="getLyDo('{{ $value['masodv'] }}')"
                                                    data-target="#tralai-modal" data-toggle="modal">
                                                    <i class="fa fa-list"></i>
                                                </button>
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
        <input type="hidden" id="nam_dt" name="nam_dt" />
        <input type="hidden" id="masodv_dt" name="masodv_dt" />
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl . 'bangluongbienche' }}', '1506672780;1506673604;1637915601;1534990562')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#modal-insolieu"
                                data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; In bảng lương biên chế </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl . 'bangluonghopdong' }}', '1506673585')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; In bảng lương hợp đồng 68 </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{ $furl . 'kinhphikhongchuyentrach?maso=' }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp kinh phí thực hiện
                                chế đố phụ cấp cán bộ không chuyên trách</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="intonghopdt('{{ $furl . 'tonghopcanboxa?maso=' }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp cán bộ chuyên trách,
                                công chức xã</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl . 'tonghopbienche' }}', '1506672780;1506673604;{{ session('admin')->mact_tuyenthem }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp biên chế, hệ số
                                lương và phụ cấp có mặt </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp hợp đồng bổ sung quỹ lương</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>

    <!--Modal thêm mới -->
    {!! Form::open(['url' => $furl . 'thong_tin', 'id' => 'frm_dutoan', 'class' => 'form-horizontal']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin dự toán lương</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label><b>Phần mềm sẽ tổng hợp cán bộ ở 02 bảng lương để tạo dự toán (ưu tiên cán bộ ở bảng lương
                                thứ nhất).</b></label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Năm tạo dự toán</label>
                        {!! Form::text('namns', date('Y') + 1, ['id' => 'namns', 'class' => 'form-control text-right']) !!}
                    </div>

                    <div class="col-md-3">
                        <label class="control-label">Mức lương cơ bản</label>
                        {!! Form::text('luongcoban', getGeneralConfigs()['luongcb'], [
                            'id' => 'luongcoban',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                        ]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Bảng lương cơ sở 1 - Tháng</label>
                        {!! Form::text('thang', '07', ['id' => 'thang', 'class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Năm</label>
                        {!! Form::text('nam', date('Y'), ['id' => 'nam', 'class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!! Form::select('manguonkp', $a_nkp, '13', ['id' => 'manguonkp', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Bảng lương cơ sở 2 - Tháng</label>
                        {!! Form::text('thang1', '07', ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Năm</label>
                        {!! Form::text('nam1', date('Y'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!! Form::select('manguonkp1', $a_nkp, '12', ['id' => 'manguonkp', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row text-center">
                    <div class="col-md-12">
                        <button type="submit" class="btn default" onclick="disable_btn(this)">Hoàn thành</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <!-- END FORM-->


    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'senddata', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi
                                gửi.</b></label>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Đơn vị tiếp nhận dữ liệu<span
                                            class="require">*</span></label>
                                    {!! Form::select('macqcq', $a_cqcq, session('admin')->macqcq, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
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

    <!--Model kinh phí cán bộ không chuyên trách-->
    <div class="modal fade" id="modal-KinhPhiKoCT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'kinhphiKoCT', 'id' => 'frm_kpkct', 'method' => 'POST']) !!}
                <input type="hidden" name="masodv" id="masodv">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin chi tiết</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Phân loại xã </label>
                                {!! Form::select('phanloaixa', getPhanLoaiXa(), null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('phanloaixa_heso', 16, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Số thôn thuộc xã biên giới, hải đảo</label>
                            {!! Form::text('sothonxabiengioi', 0, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('sothonxabiengioi_heso', 5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Số thôn thuộc xã khó khăn theo QĐ
                                30/2007/QĐ-TTg</label>
                            {!! Form::text('sothonxakhokhan', 0, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('sothonxakhokhan_heso', 5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Số thôn thuộc xã loại I, loại II</label>
                            {!! Form::text('sothonxaloai1', 0, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('sothonxaloai1_heso', 5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Số thôn thuộc xã trọng điểm, phức
                                tạp</label>
                            {!! Form::text('sothonxatrongdiem', 0, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('sothonxatrongdiem_heso', 0.5, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Số thôn thuộc xã còn lại</label>
                            {!! Form::text('sothonxakhac', 0, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mức khoán quỹ phụ cấp </label>
                                {!! Form::text('sothonxakhac_heso', 3, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            </div>
                        </div>
                    </div>
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

    <!--Model Trả lại -->
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin lý do trả lại dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
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
        function indutoan(namdt, masodv) {
            $('#nam_dt').val(namdt);
            $('#masodv_dt').val(masodv);
        }

        function add() {
            $('#create-modal').modal('show');
        }

        function disable_btn(obj) {
            // obj.prop('disabled', true);
        }

        function getKinhPhiKoCT(masodv, phanloaixa, phanloaixa_heso, sothonxabiengioi,
            sothonxabiengioi_heso, sothonxakhokhan, sothonxakhokhan_heso, sothonxatrongdiem,
            sothonxatrongdiem_heso, sothonxakhac, sothonxakhac_heso, sothonxaloai1,
            sothonxaloai1_heso) {
            $('#frm_kpkct').find("[name^='masodv']").val(masodv);
            $('#frm_kpkct').find("[name^='phanloaixa']").val(phanloaixa);
            $('#frm_kpkct').find("[name^='phanloaixa_heso']").val(phanloaixa_heso);
            $('#frm_kpkct').find("[name^='sothonxabiengioi']").val(sothonxabiengioi);
            $('#frm_kpkct').find("[name^='sothonxabiengioi_heso']").val(sothonxabiengioi_heso);
            $('#frm_kpkct').find("[name^='sothonxakhokhan']").val(sothonxakhokhan);
            $('#frm_kpkct').find("[name^='sothonxakhokhan_heso']").val(sothonxakhokhan_heso);
            $('#frm_kpkct').find("[name^='sothonxatrongdiem']").val(sothonxatrongdiem);
            $('#frm_kpkct').find("[name^='sothonxatrongdiem_heso']").val(sothonxatrongdiem_heso);
            $('#frm_kpkct').find("[name^='sothonxakhac']").val(sothonxakhac);
            $('#frm_kpkct').find("[name^='sothonxakhac_heso']").val(sothonxakhac_heso);
            $('#frm_kpkct').find("[name^='sothonxaloai1']").val(sothonxaloai1);
            $('#frm_kpkct').find("[name^='sothonxaloai1_heso']").val(sothonxaloai1_heso);
        }

        function confirmChuyen(masodv) {
            //document.getElementById("masodv").value = masodv;
            $('#frm_chuyen').find("[id^='masodv']").val(masodv);
        }

        function innangluong() {
            var masodv = $('#masodv_dt').val();
            window.open('{{ $furl }}' + 'nangluong?maso=' + masodv, '_blank');
        }

        function intonghopdt(url) {
            var masodv = $('#masodv_dt').val();
            window.open(url + masodv, '_blank');
        }

        function getLyDo(masodv) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + 'getlydo',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    masodv: masodv
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#lydo').val(data.lydo);
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });

            //$('#madvbc').val(madvbc);
            //$('#phongban-modal').modal('show');
        }

        function taobl(mabl) {
            //var tr = $(e).closest('tr');
            $('#create-modal').modal('hide');
            var nghihuu = document.getElementById("nghihuu").checked;
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //create_bangluong
            //var form = $('#create_bangluong');
            $.ajax({
                url: '{{ $furl }}' + 'create_mau',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    namdt: $('#namdt').val(),
                    luongcoban: $('#luongcoban').val(),
                    mabl: mabl,
                    nghihuu: nghihuu
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 'success') {
                        location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(message) {
                    toastr.error(message);
                }
            });

        }

        function confirm_create() {
            if ($('#namdt').val() == 0 || $('#namdt').val() == '') {
                toastr.error('Năm dự toán không được bỏ trống.', 'Lỗi!');
                $("#create_dutoan").submit(function(e) {
                    e.preventDefault();
                });
            } else {
                $("#create_dutoan").unbind('submit').submit();
            }
        }
    </script>

    @include('includes.modal.delete')
    @include('manage.dutoanluong.modal_printf')
@stop
