<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>

<!--Modal thông tin tùy chọn in bảng lương -->
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
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal" title="Bảng lương của cán bộ theo mẫu C02-HD">
                            <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button onclick="ThongTinKetXuat(false,'{{ '/nguon_kinh_phi/nangluong' }}')" type="button"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal" title="Danh sách cán bộ nâng lương">
                            <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nâng lương</button>
                    </div>
                </div>

                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" style="border-width: 0px" onclick="innangluong()"
                            class="btn btn-default btn-xs mbs">
                            <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ nâng lương</button>
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'tonghopnhucau_donvi' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(false,'{{ $inputs['furl'] . 'mautt107_m2' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng chi tiết nhu cầu kinh phí</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'tonghopnhucau_donvi_2a?mau=1' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (1) - Mẫu
                            1)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'tonghopnhucau_donvi_2a_2?mau=1' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (1)- Mẫu
                            2)</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'tonghopnhucau_donvi_2a?mau=2' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (2) - Mẫu
                            1)</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button"
                            onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'tonghopnhucau_donvi_2a_2?mau=2' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a (2)- Mẫu
                            2)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau2b' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#mautt107-modal"
                            data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu
                            (Mẫu 2b)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau2c' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí tăng thêm để thực hiện chế độ cho cán bộ
                            không chuyên trách (Mẫu 2c)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau2d' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí giảm theo nghị định số 33/2023/NĐ-CP -
                            cán bộ, công chức cấp xã (Mẫu 2d)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau2e' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí tăng theo nghị định 34/2023/NĐ-CP -
                            người hoạt động không chuyên trách ở cấp xã, ở thôn, tổ dân phố (Mẫu 2e)</button>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau2g' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp phụ cấp ưu đãi nghề theo nghị định số 05/2023/NĐ-CP (Mẫu 2g)</button>
                    </div>
                </div>      
            </div>           

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau4a' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương
                            (Mẫu 4a)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="ThongTinKetXuat(true,'{{ $inputs['furl'] . 'mau4b' }}',null)"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                            data-target="#mautt107-modal" data-toggle="modal">
                            <i class="fa fa-print"></i>&nbsp;Tổng hợp nhu cầu và nguồn thực hiện (Mẫu 4b)</button>
                    </div>
                </div>

                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="intonghopdt('{{ '/nguon_kinh_phi/printf?maso=' }}')"
                            style="border-width: 0px" class="btn btn-default btn-xs mbs">
                            <i class="fa fa-print"></i>&nbsp; Tổng hợp nhu cầu và nguồn thực hiện (Mẫu 4b)</button>
                    </div>
                </div> --}}
            </div>
            <input type="hidden" id="nam_dt" name="nam_dt" />
            <input type="hidden" id="masodv_dt" name="masodv_dt" />
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>


<!--Mẫu QD19 -->
{!! Form::open([
    'url' => (isset($inputs['furl']) ? $inputs['furl'] : '') . 'mauqd19',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mauqd19',
]) !!}
<div id="mauqd19-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
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
            <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCqd19()">Đồng
                ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open([
    'url' => (isset($inputs['furl']) ? $inputs['furl'] : '') . 'mautt107',
    'method' => 'post',
    'target' => '_blank',
    'files' => true,
    'id' => 'printf_mautt107',
]) !!}
<div id="mautt107-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
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
                        <label class="control-label">Khối/Tổ công tác</label>
                        {!! Form::select('mapb', setArrayAll($a_phongban), 'ALL', ['id' => 'mapb', 'class' => 'form-control']) !!}
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

<script>
    function ThongTinKetXuat(thang, url) {
        var form = $('#printf_mautt107');
        form.find("[id^='thang']").prop('disabled', thang);
        form.find("[id^='mapb']").prop('disabled', thang);
        form.prop('action', url);
    }

    function inblmtt107_pb() {
        $('#mabl_mautt107_pb').val($('#mabl_in').val());
        $('#mautt107_pb-modal').modal('show');
    }

    function ClickBCtt107() {
        var masodv = $('#masodv_dt').val();
        $('#printf_mautt107').find("[id^='masodv']").val(masodv);
        $('#printf_mautt107').submit();
    }
</script>
