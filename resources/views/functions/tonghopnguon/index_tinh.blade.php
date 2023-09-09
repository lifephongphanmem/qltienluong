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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ</div>
                    <div class="actions">
                        <a href="{{ url($furl . 'in_tinh?sohieu=' . $inputs['sohieu'] . '&madiban=' . $madvbc) }}"
                            class="btn btn-default btn-sm" TARGET="_blank">
                            <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp ({{ $soluong }} đơn vị)</a>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label col-md-6" style="text-align: right">Căn cứ thông tư, quyết định
                                </label>
                                <div class="col-md-6">
                                    {!! Form::select('sohieu', getThongTuQD(false), $inputs['sohieu'], [
                                        'id' => 'sohieu',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="control-label col-md-4" style="text-align: right">Địa bàn, khu vực </label>
                                <div class="col-md-8">
                                    {!! Form::select('madvbc', $a_dvbc, $madvbc, ['id' => 'madvbc', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                <div class="col-md-7">
                                    {!! Form::select('trangthai', $a_trangthai_dl, $inputs['trangthai'], [
                                        'id' => 'trangthai',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Tên đơn vị tổng hợp dữ liệu</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->tendv }}</td>
                                        <td>{{ $value->tendvcq }}</td>
                                        <td>
                                            {{-- @if ($value->masot != null) --}}
                                            @if ($value->dulieu == 'DAGUI')
                                                {{-- <a href="{{url('/du_toan/nguon_kinh_phi/ma_so='.$value['masodv'].'/in')}}" class="btn btn-default btn-xs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</a> --}}
                                                <button type="button"
                                                    onclick="innguon('{{ $value->madv }}','{{ $value->masodv }}')"
                                                    class="btn btn-default btn-xs mbs" data-target="#indt-modal"
                                                    data-toggle="modal">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</button>

                                                {{-- <button type="button" class="btn btn-default btn-xs mbs" onclick="confirmChuyen('{{$value['masodv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                Trả lại dữ liệu</button> --}}
                                            @else
                                                <button class="btn btn-danger btn-xs mbs">
                                                    <i class="fa fa-warning"></i>&nbsp; Đơn vị chưa tổng hợp dữ
                                                    liệu</button>
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

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'tralai', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
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
    <!--Model in-->
    <!-- Bỏ dùng của huyện
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

                            <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau4b' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs" 
                                data-target="#mautt107-modal" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp nhu cầu và nguồn thực hiện (Mẫu 4b)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button onclick="ThongTinKetXuat(false,'{{ '/nguon_kinh_phi/mautt107' }}')" type="button"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#mautt107-modal" data-toggle="modal"
                                title="Bảng lương của cán bộ theo mẫu C02-HD">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button"
                                onclick="intonghopdt('{{ '/nguon_kinh_phi/huyen/mautt107_m2?maso=' }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (Tổng hợp chi lương và nâng
                                lương)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="innangluong()"
                                class="btn btn-default btn-xs mbs">
                                <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nâng lương</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="nam_dt" name="nam_dt" />
                <input type="hidden" id="madv_dt" name="nam_dt" />
                <input type="hidden" id="masodv_dt" name="masodv_dt" />
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>
-->
<div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-full modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="hd-inbl" class="modal-title">In nhu cầu kinh phí</h4>
        </div>

        <div class="modal-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button onclick="ThongTinKetXuat(false,'{{ '/nguon_kinh_phi/mautt107' }}')" type="button"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal"
                            title="Bảng lương của cán bộ theo mẫu C02-HD">
                            <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button onclick="ThongTinKetXuat(false,'{{ '/nguon_kinh_phi/nangluong' }}')" type="button"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal" title="Danh sách cán bộ nâng lương">
                            <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nâng lương</button>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'tonghopnhucau_donvi' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mautt107_m2' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng chi tiết nhu cầu kinh phí</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'tonghopnhucau_donvi_2a?mau=1' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (1))</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'tonghopnhucau_donvi_2a?mau=2' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (2))</button>
                    </div>
                </div>                    
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau2b' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu
                            (Mẫu 2b)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau2c' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí tăng thêm để thực hiện chế độ cho cán bộ
                            không chuyên trách (Mẫu 2c)</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau2d' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí giảm theo nghị định số 33/2023/NĐ-CP -
                            cán bộ, công chức cấp xã (Mẫu 2d)</button>
                    </div>
                </div>                    

           
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau2e' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí tăng theo nghị định 34/2023/NĐ-CP -
                            người hoạt động không chuyên trách ở cấp xã, ở thôn, tổ dân phố (Mẫu 2e)</button>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl . 'mau2g' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Báo cáo quỹ tiền lương, phụ cấp đối với lao động theo hợp
                            đồng khu vực hành chính và đơn vị sự nghiệp
                            (Mẫu 2g)</button>
                    </div>
                </div> --}}
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau4a' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương
                            (Mẫu 4a)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $furl_dv . 'mau4b' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp nhu cầu và nguồn thực hiện (Mẫu 4b)</button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="nam_dt" name="nam_dt" />
            <input type="hidden" id="masodv_dt" name="masodv_dt" />
            <input type="hidden" id="madv_dt" name="madv_dt" />
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
    <!--Mẫu TT107 -->
    <!--
    {!! Form::open([
        'url' => (isset($furl) ? $furl : '') . 'mautt107',
        'method' => 'post',
        'target' => '_blank',
        'files' => true,
        'id' => 'printf_mautt107',
    ]) !!}
    <div id="mautt107-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">In nhu cầu kinh phí</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Tháng</label>
                            {!! Form::select('thang', getThangBC_nhucau(), 'ALL', ['id' => 'thang', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact" id="mact">
                                <option value="ALL">-- Tất cả các phân loại công tác --</option>
                                @foreach ($model_nhomct as $kieuct)
                                    <optgroup label="{{ $kieuct->tencongtac }}">
                                        <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                        @foreach ($mode_ct as $ct)
                                            <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="masodv" name="masodv" value="" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107()">Đồng
                    ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
-->
    <script>
        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        $(function() {
            $('#frm_chuyen :submit').click(function() {
                var chk = true;
                if ($('#lydo').val() == '') {
                    chk = false;
                }

                //Kết quả
                if (chk == false) {
                    toastr.error('Lý do trả lại không được bỏ trống.', 'Lỗi!');
                    $("#frm_chuyen").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("#frm_chuyen").unbind('submit').submit();
                }
            });
        });

        function getLink() {
            var sohieu = $('#sohieu').val();
            var madvbc = $('#madvbc').val();
            var trangthai = $('#trangthai').val();
            return '/chuc_nang/xem_du_lieu/nguon/tinh?sohieu=' + sohieu + '&trangthai=' + trangthai + '&madiaban=' + madvbc;
        }

        $(function() {
            $('#sohieu').change(function() {
                window.location.href = getLink();
            });

            $('#madvbc').change(function() {
                window.location.href = getLink();
            });

            $('#trangthai').change(function() {
                window.location.href = getLink();
            });
        })

        function innguon(namdt, masodv) {
            $('#madv_dt').val(namdt);
            $('#masodv_dt').val(masodv);
        }

        function intonghopdt(url) {
            var masodv = $('#masodv_dt').val();
            window.open(url + masodv, '_blank');
        }

        function innangluong() {
            var masodv = $('#masodv_dt').val();
            window.open('/nguon_kinh_phi/nangluong?maso=' + masodv, '_blank');
        }

        // function ThongTinKetXuat(thang, url) {
        //     var form = $('#printf_mautt107');
        //     form.find("[id^='thang']").prop('disabled', thang);
        //     form.prop('action', url);
        // }

        function ClickBCtt107() {
            var masodv = $('#masodv_dt').val();
            $('#printf_mautt107').find("[id^='masodv']").val(masodv);
            $('#printf_mautt107').submit();
        }
    </script>
    @include('functions.viewdata.nguonkinhphi.modal_printf')
@stop
