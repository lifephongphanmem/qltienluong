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
    <h3 class="page-title">
        BÁO CÁO TỔNG HỢP BIÊN CHẾ - TIỀN LƯƠNG
    </h3>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-body">
                    <div class="row">
                        <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold"
                            class="col-lg-12 caption text-uppercase">
                            BÁO CÁO TỔNG HỢP chi trả lương
                        </div>
                        <div class="col-lg-12">
                            <ol>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="ChiTraLuong('{{ $furl . 'tonghopluong_tinh' }}','POST', true)">Tổng hợp
                                        tình hình
                                        chi trả lương (Mẫu tổng hợp )</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="ChiTraLuong('{{ $inputs['furl_chiluong'] . 'TongHop' }}','GET', false)">Tổng
                                        hợp tình hình
                                        chi trả lương (Mẫu chi tiết )</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold;"
                            class="col-lg-12 caption text-uppercase">
                            BÁO CÁO TỔNG HỢP dự toán lương
                        </div>
                        <div class="col-lg-12">
                            <ol>
                                <li>
                                    <a href="#"
                                        onclick="inDuToan('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp biên chế,
                                        hệ số
                                        lương và phụ cấp có mặt (Mẫu tổng hợp)
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        onclick="inDuToan('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp biên
                                        chế, hệ số
                                        lương và phụ cấp có mặt (Mẫu chi tiết)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="inDuToan('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp hợp đồng
                                        bổ sung quỹ lương (Mẫu tổng hợp)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="inDuToan('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp hợp
                                        đồng bổ sung quỹ lương (Mẫu chi tiết)
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold;"
                            class="col-lg-12 caption text-uppercase">
                            BÁO CÁO TỔNG HỢP nhu cầu kinh phí
                        </div>
                        <div class="col-lg-12">
                            <ol>
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl'] . 'tonghopnhucau_tinh' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí (Tổng hợp toàn Tỉnh) </button>
                                </li>
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'tonghop_m2' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí (Theo lĩnh vực hoạt động) </button>
                                </li>

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'tonghop_pldv' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí (Theo phân loại đơn vị) </button>
                                </li>

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl'] . 'tonghopnhucau2a_tinh' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Tổng hợp toàn Tỉnh)</button>
                                </li>
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2a' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Theo lĩnh vực hoạt động)</button>
                                </li>
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2a_pldv' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Theo phân loại đơn vị)</button>
                                </li>
                                <!-- 2023.07.27 tạm thời vô hiệu hoa để đỡ bị hỏi
                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2b' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã
                                                nghỉ hưu
                                                (Mẫu 2b)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2c' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Báo cáo nhu cầu kinh phí thực hiện bảo hiểm
                                                thất nghiệp
                                                (Mẫu 2c)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2d' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp kinh phí tăng thêm để thực hiện chế
                                                độ cho cán bộ
                                                không chuyên trách (Mẫu 2d)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2dd' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Báo cáo nguồn thực hiện CCTL tiết kiệm (Mẫu 2đ)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2e' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Báo cáo nguồn thực hiện CCTL tiết kiệm trong
                                                năm (Mẫu 2e)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2g' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Báo cáo quỹ tiền lương, phụ cấp đối với lao động theo hợp đồng khu vực hành
                                                chính và đơn vị sự nghiệp (Mẫu 2g)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2h' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp phụ cấp ưu đãi giảm do điều chỉnh
                                                danh sách huyện nghèo (Mẫu 2h)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2i' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp phụ cấp thu hút giảm do điều chỉnh
                                                danh sách huyện nghèo (Mẫu 2i)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2k' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp kinh phí giảm theo nghị định số
                                                34/2019/NĐ-CP - cán bộ, công chức cấp xã
                                                (Mẫu 2k)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau2l' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp kinh phí giảm theo nghị định số
                                                34/2019/NĐ-CP - người hoạt động không chuyên trách
                                                (Mẫu 2l)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau4a' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Báo cáo nguồn kinh phí để thực hiện cải cách
                                                tiền lương (Mẫu 4a)</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'mau4b' }}',null)"
                                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                                data-target="#modal-innhucaukp" data-toggle="modal">
                                                Tổng hợp nhu cầu, nguồn thực hiện (Mẫu 4b)</button>
                                        </li>
                                        -->
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Mẫu in số liệu chi trả lương -->
    {!! Form::open(['url' => '', 'method' => 'post', 'target' => '_blank', 'files' => true, 'id' => 'frm_indutoan']) !!}
    <div id="modal-indutoan" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact[]" id="mact" multiple=true>
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

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Năm dự toán</label>
                            {!! Form::select('namns', getNam(), date('Y'), ['class' => 'form-control select2me']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                </div>

                {{-- <input type="hidden" name="masodv" />
                <input type="hidden" name="namns" /> --}}
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!--Mẫu in số liệu chi tiết-->
    {!! Form::open([
        'url' => '',
        'method' => 'post',
        'target' => '_blank',
        'files' => true,
        'id' => 'frm_insolieu_ct',
    ]) !!}
    <div id="modal-insolieu-ct" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact[]" id="mact" multiple=true>
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
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Năm dự toán</label>
                            {!! Form::select('namns', getNam(), date('Y'), ['class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Đơn vị<span class="require">*</span></label>
                            <select class="form-control select2me" id="donvi" name="madv">
                                @foreach ($model_dvbc as $donvi)
                                    <option value="{{ $donvi['madvcq'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                </div>

                {{-- <input type="hidden" name="masodv" />
                            <input type="hidden" name="namns" /> --}}
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!-- modal báo cáo tổng hợp tỉnh -->
    <div id="thoaichitra-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'frm_chitraluong',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất tổng hợp chi trả bảng lương
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Địa bàn báo cáo</label>
                            <select class="form-control select2me" id="madvbc" name="madvbc">
                                @foreach ($model_dvbc as $donvi)
                                    <option value="{{ $donvi['madvbc'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label> Tháng</label>
                            {!! Form::select('thang', $a_thang, 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label"> Năm<span class="require">*</span></label>
                            {!! Form::select('nam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label"> Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                    ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <!--Mẫu in số liệu nhu cầu kinh phí -->
    {!! Form::open([
        'url' => '',
        'method' => 'post',
        'target' => '_blank',
        'files' => true,
        'id' => 'frm_innhucaukp',
    ]) !!}
    <div id="modal-innhucaukp" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Địa bàn báo cáo</label>
                            <select class="form-control select2me" id="madvbc" name="madvbc">
                                @foreach ($model_dvbc as $donvi)
                                    <option value="{{ $donvi['madvbc'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Thông tư, quyết định</label>
                            {!! Form::select('sohieu', $a_thongtuqd, null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script type="text/javascript">
        //In chi trả lương
        function ChiTraLuong(url, method, trangthai_madvbc) {
            $('#frm_chitraluong').attr('action', url);
            $('#frm_chitraluong').attr('method', method);
            $('#frm_chitraluong').find("[name^='madvbc']").attr('disabled', trangthai_madvbc);
        }

        //In dự toán
        function inDuToan(url, mact) {
            if (mact == null) {
                $('#frm_indutoan').find("[name^='mact']").attr('disabled', true);
            } else {
                $('#frm_indutoan').find("[name^='mact']").attr('disabled', false);
                $('#frm_indutoan').find("[name^='mact']").val(mact.split(';')).trigger('change');

            }
            $('#frm_indutoan').attr('action', url);
        }

        //In nhu cầu kinh phí
        function inNhuCauKP(url, trangthai_madvbc) {
            $('#frm_innhucaukp').attr('action', url);
            $('#frm_innhucaukp').find("[name^='madvbc']").attr('disabled', trangthai_madvbc);
        }
    </script>
@stop
