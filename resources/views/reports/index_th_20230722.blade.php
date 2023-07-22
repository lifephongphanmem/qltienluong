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
                                    <!--li><a href="#" data-target="#thoaichitra-khoi-modal" data-toggle="modal" onclick="chitraluong_khoi('{{ $furl . 'khoi/chitraluong_th' }}')">Tổng hợp tình hình chi trả lương (Mẫu tổng hợp)</a></li>
                                                                                    <li><a href="#" data-target="#thoaichitra-khoi-modal" data-toggle="modal" onclick="chitraluong_khoi('{{ $furl . 'khoi/chitraluong_ct' }}')">Tổng hợp tình hình chi trả lương (Mẫu chi tiết)</a></li-->
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'khoi/chitraluong_th' }}')">Tổng hợp tình hình chi
                                            trả lương (Mẫu tổng hợp)</a></li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-ct-modal" data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'khoi/chitraluong_ct' }}')">Tổng hợp tình hình chi
                                            trả lương (Mẫu chi tiết)</a></li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'khoi/chitraluong_ctpl' }}')">Tổng hợp tình hình
                                            chi
                                            trả lương (Mẫu chi tiết - phân loại) </a></li>
                                    <li><a href="#" data-target="#thoaidutoan-khoi-modal" data-toggle="modal"
                                            onclick="dutoanluong_khoi('{{ $furl . 'khoi/dutoanluong' }}')">Dự toán
                                            lương</a>
                                    </li>
                                    <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'khoi/baocaohesoluong' }}')">Báo cáo hệ số lương
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
                            <div style="margin-bottom: 5px;margin-left: 5px;" class="col-lg-12 caption text-uppercase">
                                BÁO CÁO TỔNG HỢP chi trả lương
                            </div>
                            <div class="col-lg-12">
                                <ol>
                                    <li>
                                        <a href="#" data-target="#thoaichitra-khoi-moi-vn-modal"
                                            data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'huyen/tonghopluong_vn' }}')">
                                            Tổng hợp tình hình chi trả lương
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div style="margin-bottom: 5px;margin-left: 5px;" class="col-lg-12 caption text-uppercase">
                                BÁO CÁO TỔNG HỢP dự toán lương
                            </div>
                            <div class="col-lg-12">
                                <ol>
                                    <li>
                                        <a href="#" data-target="#thoaichitra-khoi-moi-vn-modal"
                                            data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'huyen/tonghopluong_vn' }}')">
                                            Tổng hợp tình hình chi trả lương
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <ol>
                                    <!--li><a href="#" data-target="#thoaichitra-huyen-modal" data-toggle="modal" onclick="chitraluong_huyen('{{ $furl . 'huyen/chitraluong_th' }}')">Tổng hợp tình hình chi trả lương (Mẫu tổng hợp)</a></li>
                                                                                    <li><a href="#" data-target="#thoaichitra-huyen-modal" data-toggle="modal" onclick="chitraluong_huyen('{{ $furl . 'huyen/chitraluong_ct' }}')">Tổng hợp tình hình chi trả lương (Mẫu chi tiết)</a></li-->
                                    <!-- <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                                                                        onclick="baocao('{{ $furl . 'huyen/chitraluong_th' }}')">Tổng hợp tình hình chi
                                                                                        trả lương (Mẫu tổng hợp - Mẫu 1)</a></li>
                                                                                <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                                                                        onclick="baocao('{{ $furl . 'huyen/tonghopluongCR' }}')">Tổng hợp tình hình chi
                                                                                        trả lương (Mẫu tổng hợp - Mẫu 2)</a></li> -->
                                    {{-- <li><a href="#" data-target="#thoaichitra-khoi-moi-vn-modal"
                                            data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'huyen/tonghopluong_th' }}')">Tổng hợp tình hình chi
                                            trả lương (Mẫu tổng hợp - Mẫu 3)</a></li> --}}
                                    {{-- <li><a href="#" data-target="#thoaichitra-khoi-moi-vn-modal"
                                            data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'huyen/tonghopluong_vn' }}')">Tổng hợp tình hình chi
                                            trả lương (Mẫu tổng hợp - Mẫu 3)</a></li> --}}

                                    <!-- <li><a href="#" data-target="#thoaichitra-khoi-moi-ct-modal"
                                                                                        data-toggle="modal"
                                                                                        onclick="baocao('{{ $furl . 'huyen/chitraluong_ct' }}')">Tổng hợp tình hình chi
                                                                                        trả lương (Mẫu chi tiết - Mẫu 1)</a></li>
                                                                                <li><a href="#" data-target="#thoaichitra-khoi-moi-ct-modal"
                                                                                        data-toggle="modal"
                                                                                        onclick="baocao('{{ $furl . 'huyen/chitraluong_ct_CR' }}')">Tổng hợp tình hình
                                                                                        chi trả lương (Mẫu chi tiết - Mẫu 2)</a></li> -->
                                    <!-- Bỏ không dùng 22062023
                                                                                <li><a href="#" data-target="#thoaidutoan-huyen-modal" data-toggle="modal"
                                                                                        onclick="dutoanluong_huyen('{{ $furl . 'huyen/dutoanluong' }}')">Dự toán lương
                                                                                        (mẫu 1)</a></li>
                                                                                <li><a href="#" data-target="#thoaidutoan-huyen-modal" data-toggle="modal"
                                                                                        onclick="dutoanluong_huyen('{{ $furl . 'huyen/dutoanluongCR' }}')">Dự toán lương
                                                                                        (mẫu 2)</a></li> -->
                                    {{-- <li><a href="#" data-target="#thoaidutoan-huyen-modal" data-toggle="modal"
                                            onclick="dutoanluong_huyen('{{ $furl . 'huyen/nguonkinhphiCR' }}')">Tổng hợp
                                            nguồn kinh phí (mẫu 1)</a></li> --}}
                                    {{-- 2023/22/06 Tạm thời bỏ để thiết kế lại --}}
                                    {{-- <li><a href="#" data-target="#thoaibaocaohesoluong-khoi-modal"
                                            data-toggle="modal"
                                            onclick="baocao('{{ $furl . 'huyen/baocaohesoluong' }}')">Báo cáo hệ số lương

                                            của đơn vị có mặt</a></li>
                                    <li><a href="#"
                                            onclick="insolieu('{{ $furl_th . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"                                            
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp biên chế, hệ số
                                            lương và phụ cấp có mặt (Mẫu 01)</a></li>
                                    <li><a href="#"
                                            onclick="insolieu('{{ $furl_th . 'tonghopbienche_m2' }}','1506672780;1506673604;1637915601')"                                            
                                            data-target="#modal-insolieu" data-toggle="modal">
                                            Tổng hợp biên chế, hệ số
                                            lương và phụ cấp có mặt (Mẫu 02)</a></li>
                                    <li>
                                        <a href="#"
                                            onclick="insolieu('{{ $furl_th . 'tonghophopdong' }}','1506673585')"                                          
                                            data-target="#modal-insolieu" data-toggle="modal">
                                           Tổng hợp hợp đồng bổ sung quỹ lương (Mẫu
                                            01)</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            onclick="insolieu('{{ $furl_th . 'tonghophopdong_m2' }}','1506673585')"                                            
                                            data-target="#modal-insolieu" data-toggle="modal">
                                           Tổng hợp hợp đồng bổ sung quỹ lương (Mẫu
                                            02)</a>
                                    </li>
                                    

                                        <!-- Tạm thời bỏ, không dùng 20062023 -->

                                    {{-- <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2a1' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/1)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2a2' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a/2)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2a' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện nghị định 38/2019/NĐ-CP (Mẫu 2a)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2b' }}')">Báo cáo tổng hợp
                                            quỹ trợ cấp tăng thêm của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a>
                                    </li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2c' }}')">Báo cáo nhu cầu
                                            kinh phí thực hiện BHTN theo nghị định 28/2015 (Mẫu 2c)</a></li>
                                    <!--li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{ $furl . 'maubckpbhtn' }}')">Báo cáo nhu cầu kinh phí thực hiện bảo hiểm thất nghiệp theo nghị định 28/2015/NĐ-CP</a></li-->
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2d' }}')">Tổng hợp kinh phí
                                            tăng thêm để thực hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu
                                            2d)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2dd' }}')">Báo cáo nguồn
                                            thực hiện CCTL tiết kiệm từ việc thực hiện tinh giảm biên chế (Mẫu 2đ)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2e' }}')">Báo cáo nguồn
                                            thực hiện CCTL tiết kiệm từ việc thay đổi cơ chế tự chủ (Mẫu 2e)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2g' }}')">Báo cáo quỹ tiền
                                            lương, phụ cấp đối với lao động theo hợp đồng khu vực hành chính và đơn vị sự
                                            nghiệp (Mẫu 2g)</a></li>
                                    <!--li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau2h' }}')">Tổng hợp phụ cấp thu hút tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2h)</a></li-->
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4a' }}')">Báo cáo nguồn
                                            kinh phí để thực hiện cải cách tiền lương (Mẫu 4a)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4b' }}')">Tổng hợp nhu cầu,
                                            nguồn thực hiện nghị định 38/2019/NĐ-CP (Mẫu 4b)</a></li>
                                    <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                            onclick="baocao('{{ '/bao_cao/thong_tu_67/huyen/mau4bbs' }}')">Tổng hợp nhu
                                            cầu, nguồn thực hiện nghị định 38/2019/NĐ-CP (Mẫu 4b bổ sung)</a></li> --}}

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
                        <div class="col-md-4">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Năm</label>
                            {!! Form::select('namns', getNam(), date('Y'), ['id' => 'namns', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <input type="hidden" name="masodv" />
                {{-- <input type="hidden" name="namns" /> --}}
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div id="thoaibaocaohesoluong-khoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoai_baocaohesoluong',
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
                            {!! Form::select('tuthang', getThang(), 'ALL', ['id' => 'tuthang', 'class' => 'form-control']) !!}
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
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh', getDonViTinh(), '1', ['id' => 'donvitinh', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <label class="col-md-4 control-label"> </label>
                    <input type="checkbox" name="excelthsl" id="excelthsl" />
                    Xuất dữ liệu ra file excel
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="urlbcsoluong" name="urlbcsoluong">
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
    
    <div id="thoaichitra-khoi-moi-vn-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_khoi_moi_vn',
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
                            {!! Form::select('tuthang', getThang(), date('m'), ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai', $a_phanloai, null, ['id' => 'phanloai', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div> --}}
                    {{-- @if (!session('admin')->quanlykhuvuc) --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại công tác<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="phanloaict" name="phanloaict[]" multiple=true>
                                @foreach ($model_phanloaict as $phanloaict)
                                    <option value="{{ $phanloaict['mact'] }}">{{ $phanloaict['tenct'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- @endif --}}
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
    <div id="thoaichitra-khoi-moi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
    <div id="thoaichitra-khoi-moi-ct-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_khoi_ct_moi',
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
                            {!! Form::select('nam', getNam(), date('Y'), ['id' => 'nam', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvi" name="donvi">
                                @foreach ($model_dv as $donvi)
                                    <option value="{{ $donvi['madv'] }}">{{ $donvi['tendv'] }}</option>
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
                    <input type="checkbox" name="excelct" id="excelct" />
                    Xuất dữ liệu ra file excel
                    <input type="hidden" id="urlbcluongct" name="urlbcluongct">
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
    <div id="thoaibangluong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaibangluong',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('thang', getThang(), null, ['id' => 'thang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('nam', getNam(), date('Y'), ['id' => 'nam', 'class' => 'form-control']) !!}
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
    <div id="thoaichitra-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                        <label class="col-md-4 control-label"> Từ tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', getThang(), '01', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang', getThang(), '12', ['id' => 'denthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
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

    <div id="thoaidutoan-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaidutoan',
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

    <div id="thoaichitra-khoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_khoi',
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
                        <label class="col-md-4 control-label"> Từ tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', getThang(), '01', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang', getThang(), '12', ['id' => 'denthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
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

    <div id="thoaidutoan-khoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaidutoan_khoi',
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

    <div id="thoaichitra-huyen-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaichitra_huyen',
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
                        <label class="col-md-4 control-label"> Từ tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang', getThang(), '01', ['id' => 'tuthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang', getThang(), '12', ['id' => 'denthang', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam', getNam(), date('Y'), ['id' => 'tunam', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
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

    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaibc67',
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
                                </br>
                                <label class="col-md-4 control-label"> </label>
                                <input type="checkbox" name="inheso" id="inheso" />
                                In theo hệ số
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="urlbc67" id="urlbc67" value="">
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

    <script>
        function baocaobangluong(url) {
            $('#thoaibangluong').attr('action', url);
        }

        function chitraluong(url) {
            $('#thoaichitra').attr('action', url);
        }

        function dutoanluong(url) {
            $('#thoaidutoan').attr('action', url);
        }

        function chitraluong_khoi(url) {
            $('#thoaichitra_khoi').attr('action', url);
        }

        function dutoanluong_khoi(url) {
            $('#thoaidutoan_khoi').attr('action', url);
        }

        function chitraluong_huyen(url) {
            $('#thoaichitra_huyen').attr('action', url);
        }

        function dutoanluong_huyen(url) {
            $('#thoaidutoan_huyen').attr('action', url);
        }

        function chitraluong_khoi_moi(url) {
            $('#thoaichitra_khoi_moi').attr('action', url);
        }

        function chitraluong_khoi_moi(url) {
            $('#thoaichitra_khoi_moi_vn').attr('action', url);
        }

        function chitraluong_khoi_ct_moi(url) {
            $('#thoaichitra_khoi_ct_moi').attr('action', url);
        }

        function baocaohesoluong(url) {
            $('#thoai_baocaohesoluong').attr('action', url);
        }
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
    </script>

    <script type="text/javascript">
        function baocao(url) {
            $('#urlbc67').val(url);
            $('#urlbcluongct').val(url);
            $('#urlbcluong').val(url);
            $('#urlbcsoluong').val(url);

        }
        window.onsubmit = function() {
            document.thoaibc67.action = get_action67();
            document.thoaichitra_khoi_moi.action = get_action67();
            document.thoaichitra_khoi_ct_moi.action = get_action67();
            document.thoai_baocaosoluong.action = get_action67();
        }

        function get_action67() {
            var url = $('#urlbc67').val();
            var url1 = $('#urlbcluong').val();
            var url2 = $('#urlbcluongct').val();
            var url3 = $('#urlbcsoluong').val();
            if ($("input[name='excel']:checked").length == 1) {
                url = $('#urlbc67').val();
                $('#thoaibc67').attr('action', url);
            } else {
                $('#thoaibc67').attr('action', url);
            }
            if ($("input[name='excelth']:checked").length == 1) {
                url1 = $('#urlbcluong').val() + 'excel';
                $('#thoaichitra_khoi_moi').attr('action', url1);
            } else {
                $('#thoaichitra_khoi_moi').attr('action', url1);
            }
            $('#thoaichitra_khoi_moi_vn').attr('action', url1);
            if ($("input[name='excelct']:checked").length == 1) {
                url2 = $('#urlbcluongct').val() + 'excel';
                $('#thoaichitra_khoi_ct_moi').attr('action', url2);
            } else {
                $('#thoaichitra_khoi_ct_moi').attr('action', url2);
            }
            if ($("input[name='excelthsl']:checked").length == 1) {
                url3 = $('#urlbcsoluong').val() + 'excel';
                $('#thoai_baocaohesoluong').attr('action', url3);
            } else {
                $('#thoai_baocaohesoluong').attr('action', url3);
            }
        }
    </script>


@stop
