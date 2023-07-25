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
                                        onclick="ChiTraLuong('{{ $furl . 'tonghopluong_tinh' }}','POST')">Tổng hợp tình hình
                                        chi trả
                                        lương (Mẫu tổng hợp )</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal"
                                        onclick="ChiTraLuong('{{ $furl . 'tonghopluong_tinh' }}','GET')">Tổng hợp tình hình
                                        chi trả
                                        lương (Mẫu chi tiết )</a>
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

    <script type="text/javascript">
        function ChiTraLuong(url) {
            $('#urlbc').val(url);
            $('#urlbcluong_ct').val(url);
        }

        function dutoanluong_huyen(url) {
            $('#thoaidutoan_huyen').attr('action', url);
        }
    </script>
@stop
