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
                                        chi trả lương 
                                        {{-- chi trả lương (Mẫu tổng hợp ) --}}
                                    </a>
                                </li>
                                  <!-- tạm ẩn 25/9/2023 -->
                                {{-- <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="ChiTraLuong('{{ $inputs['furl_chiluong'] . 'TongHop' }}','GET', false)">Tổng
                                        hợp tình hình
                                        chi trả lương (Mẫu chi tiết )</a>
                                </li> --}}
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
                                        {{-- lương và phụ cấp có mặt (Mẫu tổng hợp) --}}
                                        lương và phụ cấp có mặt 
                                    </a>
                                </li>
                                    <!-- tạm ẩn 25/9/2023 -->
                                {{-- <li>
                                    <a href="#"
                                        onclick="inDuToan('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp biên
                                        chế, hệ số
                                        lương và phụ cấp có mặt (Mẫu chi tiết)
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="#" onclick="inDuToan('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp hợp đồng
                                        bổ sung quỹ lương 
                                        {{-- bổ sung quỹ lương (Mẫu tổng hợp) --}}
                                    </a>
                                </li>

                                <!-- tạm ẩn 25/9/2023 -->
                                {{-- <li>
                                    <a href="#" onclick="inDuToan('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-indutoan" data-toggle="modal">Dự toán lương - Tổng hợp hợp
                                        đồng bổ sung quỹ lương (Mẫu chi tiết)
                                    </a>
                                </li> --}}
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
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl'] . 'tonghopnhucau_tinh' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí (Tổng hợp toàn Tỉnh) </button>
                                </li> --}}
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/tonghopnhucau' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí </button>
                                </li> --}}

                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ $inputs['furl_nhucaukp'] . 'tonghop_pldv' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal"
                                        title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                        Bảng tổng hợp nhu cầu kinh phí (Theo phân loại đơn vị) </button>
                                </li> --}}

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2a_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2a' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a)</button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2a' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Chi tiết)</button>
                                </li> --}}

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2b_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2b' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu (Mẫu 2b)</button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2b' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu (Mẫu 2b)</button>
                                </li> --}}

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2c_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2c' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí tăng thêm để thực hiện chế độ cho cán bộ
                                        không chuyên trách (Mẫu 2c)</button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2c' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí tăng thêm để thực hiện chế độ cho cán bộ
                                        không chuyên trách (Mẫu 2c)</button>
                                </li> --}}

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2d_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2d' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí giảm theo nghị định số 33/2023/NĐ-CP -
                                        cán bộ, công chức cấp xã (Mẫu 2d)</button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2d' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí giảm theo nghị định số 33/2023/NĐ-CP -
                                        cán bộ, công chức cấp xã (Mẫu 2d)</button>
                                </li> --}}

                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2e_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2e' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí tăng theo nghị định 34/2023/NĐ-CP -
                                        người hoạt động không chuyên trách ở cấp xã, ở thôn, tổ dân phố (Mẫu 2e)</button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau2e' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp kinh phí tăng theo nghị định 34/2023/NĐ-CP -
                                        người hoạt động không chuyên trách ở cấp xã, ở thôn, tổ dân phố (Mẫu 2e)</button>
                                </li> --}}
                                
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau4a_tonghop' }}','{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau4a' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương (Mẫu 4a)
                                    </button>
                                </li>
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau4a' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương (Mẫu 4a)
                                    </button>
                                </li> --}}
                                
                                {{-- <li>
                                    <button type="button"
                                        onclick="inNhuCauKP('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau4b_tonghop' }}',true)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp nhu cầu, nguồn thực hiện (Mẫu 4b - Tổng hợp)</button>
                                </li> --}}
                                <li>
                                    <button type="button"
                                        onclick="inNhuCauKP_mau4b('{{ '/tong_hop_bao_cao/nhu_cau_kinh_phi/mau4b' }}',false)"
                                        style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                        data-target="#modal-innhucaukp" data-toggle="modal">
                                        Tổng hợp nhu cầu, nguồn thực hiện (Mẫu 4b)</button>
                                </li>
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
                            {!! Form::select('donvitinh', getDVT(), null, ['class' => 'form-control select2me']) !!}
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
                                @foreach ($a_dvbc as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDVT(), null, ['class' => 'form-control select2me']) !!}
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
                                @foreach ($a_dvbc as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
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
                            {!! Form::select('donvitinh', getDVT(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
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
    <input type="hidden" name="madv" value="{{ session('admin')->madv }}">
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
                            {!! Form::select('madvbc', setArrayAll($a_dvbc), null, ['class' => 'form-control', 'required']) !!}
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
                            {!! Form::select('donvitinh', getDVT(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    <div class="row" id='maukhac'>
                        <div class="col-md-12">
                            <label class="control-label">Mẫu kết xuất</label>
                            {!! Form::select('mauketxuat', ['th'=>'Tổng hợp','ct'=>'Chi tiết'], null, ['class' => 'form-control', 'required','id'=>'mauketxuat']) !!}
                        </div>
                    </div>
                </div>
                <input type="hidden" name="url_th" id="url_th">
                <input type="hidden" name="url_ct" id="url_ct">

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
        function inNhuCauKP_mau4b(url, trangthai_madvbc) {
            $('#maukhac').hide();
            $('#frm_innhucaukp').attr('action', url);
            $('#frm_innhucaukp').find("[name^='madvbc']").attr('disabled', trangthai_madvbc);
        }
        function inNhuCauKP(url_th,url_ct, trangthai_madvbc) {
            $('#maukhac').show();
            $('#mauketxuat').val('th');
            $('#frm_innhucaukp').attr('action', url_th);
            $('#url_th').val(url_th);
            $('#url_ct').val(url_ct);
            $('#frm_innhucaukp').find("[name^='madvbc']").attr('disabled', trangthai_madvbc);
        }
        $('#mauketxuat').on('change',function(){
            mauketxuat=$('#mauketxuat').val();
            if(mauketxuat == 'th'){
                url=$('#url_th').val();
                $('#frm_innhucaukp').find("[name^='madvbc']").val('ALL');
                $('#frm_innhucaukp').find("[name^='madvbc']").attr('disabled', true);
            }else{
                url=$('#url_ct').val();
                $('#frm_innhucaukp').find("[name^='madvbc']").attr('disabled', false);
            }
            $('#frm_innhucaukp').attr('action', url);
            

        });
    </script>
@stop
