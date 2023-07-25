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
                        <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold" class="col-lg-12 caption text-uppercase">
                            BÁO CÁO TỔNG HỢP chi trả lương
                        </div>
                        <div class="col-lg-12">
                            <ol>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'tonghopluong_tinh' }}')">Tổng hợp tình hình chi trả
                                        lương (Mẫu tổng hợp )</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'tonghopluong_tinh' }}')">Tổng hợp tình hình chi trả
                                        lương (Mẫu chi tiết )</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold;" class="col-lg-12 caption text-uppercase">
                            BÁO CÁO TỔNG HỢP dự toán lương
                        </div>
                        <div class="col-lg-12">
                            <ol>
                                <li>
                                    <a href="#"
                                        onclick="insolieu('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                        data-target="#modal-insolieu" data-toggle="modal">Dự toán lương - Tổng hợp biên chế,
                                        hệ số
                                        lương và phụ cấp có mặt (Mẫu tổng hợp)
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        onclick="insolieu('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                        data-target="#modal-insolieu-ct" data-toggle="modal">Dự toán lương - Tổng hợp biên
                                        chế, hệ số
                                        lương và phụ cấp có mặt (Mẫu chi tiết)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="insolieu('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-insolieu" data-toggle="modal">Dự toán lương - Tổng hợp hợp đồng
                                        bổ sung quỹ lương (Mẫu tổng hợp)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="insolieu('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                        data-target="#modal-insolieu-ct" data-toggle="modal">Dự toán lương - Tổng hợp hợp
                                        đồng bổ sung quỹ lương (Mẫu chi tiết)
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="namns" name="namns" />
        <input type="hidden" id="masodv" name="masodv" />
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
                                onclick="insolieu('{{ $furl . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#modal-insolieu"
                                data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp biên chế, hệ số
                                lương và phụ cấp có mặt (Mẫu 01)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl . 'tonghophopdong' }}','1506673585')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs" data-target="#modal-insolieu"
                                data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp hợp đồng bổ sung quỹ lương (Mẫu 01)</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>

    <!--Mẫu in số liệu -->
    {!! Form::open(['url' => '', 'method' => 'post', 'target' => '_blank', 'files' => true, 'id' => 'frm_insolieu']) !!}
    <div id="modal-insolieu" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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

    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaibc',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất báo cáo</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <!--
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::select(
                                                                'thang',
                                                                [
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
                                                                ],
                                                                null,
                                                                ['id' => 'thang', 'class' => 'form-control'],
                                                            ) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                                                        <div class="col-md-8">
                                                            {!! Form::select('nam', getNam(), date('Y'), ['id' => 'nam', 'class' => 'form-control']) !!}
                                                        </div>
                                                    </div>
                                                    -->
                            @if (session('admin')->level == 'H')
                                <div class="form-group">
                                    <label class="col-md-4 control-label"> Đơn vị<span class="require">*</span></label>
                                    <div class="col-md-8">
                                        <select class="form-control select2me" name="madv" id="madv">
                                            <option value="">Tất cả các đơn vị</option>
                                            @if (isset($model_dv))
                                                @foreach ($model_dv as $dv)
                                                    <option value="{{ $dv->madv }}">{{ $dv->tendv }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if (session('admin')->level == 'T')
                                <div class="form-group">
                                    <label class="col-md-4 control-label"> Khu vực, địa bàn<span
                                            class="require">*</span></label>
                                    <div class="col-md-8">
                                        <select class="form-control select2me" id="madv" name="madv"
                                            class="form-control">
                                            <option value="">Tất cả các đơn vị</option>

                                            @if (session('admin')->username != 'khthso' && isset($model_dvbc))
                                                @foreach ($model_dvbc as $dv)
                                                    <option value="{{ $dv->madvbc }}">{{ $dv->tendvbc }}</option>
                                                @endforeach
                                            @endif
                                            @if (session('admin')->username == 'khthso' && isset($model_dvbcT))
                                                @foreach ($model_dvbcT as $dvT)
                                                    <option value="{{ $dvT->madv }}">{{ $dvT->tendv }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-4 control-label"> Thông tư:<span class="require">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2me" id="sohieu" name="sohieu"
                                        class="form-control">
                                        @foreach ($model_thongtu as $tt)
                                            <option value="{{ $tt->sohieu }}">{{ $tt->tenttqd }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"> Đơn vị tính</label>
                                <div class="col-md-8">
                                    {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"> </label>
                                <input type="checkbox" name="inchitiet" />
                                <label> In chi tiết các đơn vị</label>
                                </br>
                                <label class="col-md-4 control-label"> </label>
                                <input type="checkbox" name="excel" id="excel" />
                                Xuất dữ liệu ra file excel
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="urlbc" id="urlbc" value="">
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

    <!-- modal báo cáo tổng hợp tỉnh -->
    <div id="thoaichitra-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_khoi_moi',
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
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', $a_thang, 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, date('Y'), ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                   
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="urlbcluong" name="urlbcluong">
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

    <!-- modal báo cáo tổng hợp tỉnh -->
    <div id="thoaichitra-khoi-moi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra',
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
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', $a_thang, 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, date('Y'), ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    @if (!session('admin')->quanlykhuvuc)
                        <div class="form-group">
                            <label class="col-md-4 control-label">Phân loại công tác<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control select2me" id="phanloaict" name="phanloaict">
                                    <option value="">--Chọn tất cả--</option>

                                    @foreach ($model_phanloaict as $phanloaict)
                                        <option value="{{ $phanloaict['mact'] }}">{{ $phanloaict['tenct'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvi" name="madv">
                                @foreach ($model_dvbc as $donvi)
                                    <option value="{{ $donvi['madvcq'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <label class="col-md-4 control-label"> </label>
                    <input type="checkbox" name="excelth" id="excelth" />
                    Xuất dữ liệu ra file excel
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="urlbcluong_ct" name="urlbcluong">
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

    <!-- modal báo cáo tổng hợp theo đơn vị báo cáo -->
    <div id="thoaichitra-huyen-moi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_huyen_moi',
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
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', $a_thang, 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, date('Y'), ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    @if (!session('admin')->quanlykhuvuc)
                        <div class="form-group">
                            <label class="col-md-4 control-label">Phân loại công tác<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control select2me" id="phanloaict" name="phanloaict">
                                    <option value="">--Chọn tất cả--</option>

                                    @foreach ($model_phanloaict as $phanloaict)
                                        <option value="{{ $phanloaict['mact'] }}">{{ $phanloaict['tenct'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvi" name="donvi">
                                @foreach ($model_dvbc as $donvi)
                                    <option value="{{ $donvi['madvbc'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <label class="col-md-4 control-label"> </label>
                    <input type="checkbox" name="excelth" id="excelth" />
                    Xuất dữ liệu ra file excel
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="urlbcluong" name="urlbcluong">
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

    <!-- modal báo cáo chi tiết -->
    <div id="thoaichitra-huyen-ct-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_huyen_ct',
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
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', $a_thang, 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, date('Y'), ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                    @if (!session('admin')->quanlykhuvuc)
                        <div class="form-group">
                            <label class="col-md-4 control-label">Phân loại công tác<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control select2me" id="phanloaict" name="phanloaict">
                                    <option value="">--Chọn tất cả--</option>

                                    @foreach ($model_phanloaict as $phanloaict)
                                        <option value="{{ $phanloaict['mact'] }}">{{ $phanloaict['tenct'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị báo cáo<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvi-ct" name="donvi">
                                <option value="">--Chọn đơn vị--</option>
                                @foreach ($model_dvbc as $donvi)
                                    <?php $model_donvi = $m_donvi->where('madvbc', $donvi->madvbc); ?>
                                    <option value="{{ $donvi['madvbc'] }}">{{ $donvi->tendvbc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị báo cáo cấp dưới<span
                                class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvicapduoi" name="donvicapduoi">
                                {{-- @foreach ($model_donvi as $donvi)
                                    <option value="{{ $donvi['madvbc'] }}">{{ $donvi->tendv }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <label class="col-md-4 control-label"> </label>
                    <input type="checkbox" name="excelth" id="excelth" />
                    Xuất dữ liệu ra file excel
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="urlbcluong" name="urlbcluong">
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

    <!-- modal dự toán -->
    <div id="thoaidutoan-huyen-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaidutoan_huyen',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất dự toán lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('namns', getNam(), date('Y'), ['id' => 'namns', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, date('Y'), ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
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

    <script type="text/javascript">
        function baocao(url) {
            $('#urlbc').val(url);
            $('#urlbcluong_ct').val(url);
        }
        window.onsubmit = function() {
            document.thoaibc.action = get_action();
            document.thoaichitra_khoi_moi.action = get_action();
            document.thoaichitra.action = get_action();
            document.thoaichitra_huyen_moi.action = get_action();
            document.thoaichitra_huyen_ct.action = get_action();
        }

        function get_action() {
            var url = $('#urlbc').val();
            if ($("input[name='excel']:checked").length == 1) {
                url = $('#urlbc').val();
                $('#thoaibc').attr('action', url);
            } else
                $('#thoaibc').attr('action', url);
            $('#thoaichitra').attr('action', url);
            $('#thoaichitra_khoi_moi').attr('action', url);
            $('#thoaichitra_huyen_moi').attr('action', url);
            $('#thoaichitra_huyen_ct').attr('action', url);
        }

        function dutoanluong_huyen(url) {
            $('#thoaidutoan_huyen').attr('action', url);
        }
        //In dữ liệu
        function insolieu(url, mact) {
            if (mact == null) {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', true);
                $('#frm_insolieuct').find("[name^='mact']").attr('disabled', true);
            } else {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', false);
                $('#frm_insolieu').find("[name^='mact']").val(mact.split(';')).trigger('change');
                $('#frm_insolieu_ct').find("[name^='mact']").attr('disabled', false);
                $('#frm_insolieu_ct').find("[name^='mact']").val(mact.split(';')).trigger('change');
            }
            $('#frm_insolieu').attr('action', url);
            $('#frm_insolieu_ct').attr('action', url);
            // $('#frm_insolieu').find("[name^='masodv']").val($('#masodv').val());
            // $('#frm_insolieu').find("[name^='namns']").val($('#namns').val());
        }

        $(document).ready(function() {
            $('#donvi-ct').on('change', function() {
                $('#donvicapduoi').find('.baocaoct').remove();
                var donvi = $('#donvi-ct').val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ $furl }}' + 'danhsach',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        donvi: donvi
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        $('#donvicapduoi').append(data)
                    }
                })
            })
        })
    </script>
@stop
