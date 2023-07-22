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
    <link href="{{ url('assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css"
        xmlns="http://www.w3.org/1999/html" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop


@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}">
    </script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/pages/scripts/form-wizard.js') }}"></script>

    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>

    @include('includes.script.scripts')
@stop
@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            @if (session('admin')->quanlynhom && !session('admin')->quanlykhuvuc)
                <div class="portlet box">
                    <div class="portlet-header">
                        MẪU BÁO CÁO TỔNG HỢP TỪ CÁC ĐƠN VỊ CẤP DƯỚI
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <ol>
                                    <!--li><a href="#" data-target="#thoaichitra-khoi-modal" data-toggle="modal" onclick="chitraluong_khoi('{{ $inputs['furl'] . 'khoi/chitraluong_th' }}')">Tổng hợp tình hình chi trả lương (Mẫu tổng hợp)</a></li>
                                                                                                                            <li><a href="#" data-target="#thoaichitra-khoi-modal" data-toggle="modal" onclick="chitraluong_khoi('{{ $inputs['furl'] . 'khoi/chitraluong_ct' }}')">Tổng hợp tình hình chi trả lương (Mẫu chi tiết)</a></li-->
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $inputs['furl'] . 'khoi/chitraluong_th' }}')">Tổng hợp tình
                                            hình chi
                                            trả lương (Mẫu tổng hợp)</a></li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-ct-modal" data-toggle="modal"
                                            onclick="baocao('{{ $inputs['furl'] . 'khoi/chitraluong_ct' }}')">Tổng hợp tình
                                            hình chi
                                            trả lương (Mẫu chi tiết)</a></li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $inputs['furl'] . 'khoi/chitraluong_ctpl' }}')">Tổng hợp
                                            tình hình
                                            chi
                                            trả lương (Mẫu chi tiết - phân loại) </a></li>
                                    <li><a href="#" data-target="#thoaidutoan-khoi-modal" data-toggle="modal"
                                            onclick="dutoanluong_khoi('{{ $inputs['furl'] . 'khoi/dutoanluong' }}')">Dự
                                            toán
                                            lương</a>
                                    </li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $inputs['furl'] . 'khoi/baocaohesoluong' }}')">Báo cáo hệ
                                            số lương
                                            của
                                            đơn vị có mặt</a></li>

                                    <!--li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau2a1') }}" target="_blank">Báo cáo nhu cầu kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/1)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau2a2') }}" target="_blank">Báo cáo nhu cầu kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/2)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau2b') }}" target="_blank">Báo cáo tổng hợp quỹ trợ cấp tăng thêm của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau2c') }}" target="_blank">Báo cáo nhu cầu kinh phí thực hiện BHTN theo nghị định 28/2015 (Mẫu 2c)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau2d') }}" target="_blank">Tổng hợp kinh phí tăng thêm để thực hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu 2d)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau4a') }}" target="_blank">Báo cáo nguồn kinh phí (Mẫu 4a)</a></li>
                                                                                                                            <li><a href="{{ url('/bao_cao/thong_tu_67/khoi/mau4b') }}" target="_blank">Tổng hợp nhu cầu, nguồn kinh phí (Mẫu 4b)</a></li-->

                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2a1' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/1)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2a2' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/2)</a></li>

                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2b' }}')">Báo cáo tổng hợp
                                            quỹ trợ cấp tăng thêm của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a>
                                    </li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2c' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện BHTN theo nghị định 28/2015 (Mẫu 2c)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2d' }}')">Tổng hợp kinh phí
                                            tăng thêm để thực hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu
                                            2d)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4a' }}')">Báo cáo nguồn kinh
                                            phí để thực hiện cải cách tiền lương (Mẫu 4a)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4b' }}')">Tổng hợp nhu cầu,
                                            nguồn thực hiện nghị định 38/2019/NĐ-CP (Mẫu 4b)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4bbs' }}')">Tổng hợp nhu
                                            cầu, nguồn thực hiện nghị định 38/2019/NĐ-CP (Mẫu 4b bổ sung)</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>lương
                </div>
            @endif

            @if (session('admin')->quanlykhuvuc)
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption text-uppercase">
                            MẪU BÁO CÁO TỔNG HỢP TRÊN TOÀN ĐỊA BÀN
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div style="margin-bottom: 5px;margin-left: 5px;font-weight: bold" class="col-lg-12 caption text-uppercase">
                                BÁO CÁO TỔNG HỢP chi trả lương
                            </div>
                            <div class="col-lg-12">
                                <ol>
                                    <li>
                                        <a href="#" data-target="#modal-chitraluong" data-toggle="modal"
                                            onclick="inchitraluong('/chuc_nang/tong_hop_luong/huyen/TongHop')">
                                            Tổng hợp tình hình chi trả lương
                                        </a>
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
                                        <button type="button"
                                            onclick="insolieu('{{ $inputs['furl_dutoan'] . 'kinhphikhongchuyentrach' }}',null)"
                                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp kinh phí thực hiện chế đố phụ cấp cán bộ không chuyên trách</button>
                                    </li>

                                    <li>
                                        <button type="button"
                                            onclick="insolieu('{{ $inputs['furl_dutoan'] . 'tonghopcanboxa' }}','1506672780;1506673604;1637915601')"
                                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp cán bộ chuyên trách, công chức xã</button>
                                    </li>

                                    <li>
                                        <button type="button"
                                            onclick="insolieu('{{ $inputs['furl_dutoan'] . 'tonghopcanbohdnd' }}','1536402868;1536402870;1536459380;1536459382;1558600713;1558945077')"
                                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp đại biểu HĐND; cấp uỷ viên</button>
                                    </li>

                                    <li>
                                        <button type="button"
                                            onclick="insolieu('{{ $inputs['furl_dutoan'] . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
                                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp biên chế, hệ số lương và phụ cấp có mặt
                                        </button>
                                    </li>

                                    <li>
                                        <button type="button"
                                            onclick="insolieu('{{ $inputs['furl_dutoan'] . 'tonghophopdong' }}','1506673585')"
                                            style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp hợp đồng bổ sung quỹ lương
                                        </button>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!--Mẫu in số liệu -->
    {!! Form::open(['url' => '', 'method' => 'post', 'target' => '_blank', 'files' => true, 'id' => 'frm_insolieu']) !!}
    {{-- Các trường dữ liệu ẩn --}}    
    <input type="hidden" name="macqcq" value="{{ $inputs['madv'] }}">
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
                        <div class="col-md-6">
                            <label class="control-label">Năm</label>
                            {!! Form::select('namns', getNam(), date('Y'), ['id' => 'namns', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-3">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-3">
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

    <div id="modal-chitraluong" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'get',
            'id' => 'frm_chitraluong',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        {{-- Các trường dữ liệu ẩn --}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="macqcq" value="{{ $inputs['madv'] }}">
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
                            {!! Form::select('thang', getThang(), date('m'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('nam', getNam(), date('Y'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại công tác<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="phanloaict" name="phanloaict[]" multiple=true>
                                @foreach ($model_phanloaict as $phanloaict)
                                    <option value="{{ $phanloaict['mact'] }}">{{ $phanloaict['tenct'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    {{-- @endif --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script>
        //In dữ liệu
        function insolieu(url, mact) {
            if (mact == null) {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', true);
            } else {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', false);
                $('#frm_insolieu').find("[name^='mact']").val(mact.split(';')).trigger('change');
            }
            $('#frm_insolieu').attr('action', url);
            $('#frm_insolieu').find("[name^='masodv']").val($('#masodv').val());
            $('#frm_insolieu').find("[name^='namns']").val($('#namns').val());
        }
        //

        function inchitraluong(url) {
            $('#frm_chitraluong').attr('action', url);
        }
    </script>
@stop
