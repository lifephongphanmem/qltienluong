<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
?>
@extends('main')

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
                        <div class="col-lg-12">
                            <ol>
                                <li><a href="#" data-target="#thoaichitra-khoi-moi-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'tonghopluong_tinh_CR' }}')">Tổng hợp tình hình chi trả
                                        lương (Mẫu tổng hợp tỉnh)</a></li>
                                <li><a href="#" data-target="#thoaichitra-huyen-moi-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'tonghopluong_huyen_CR' }}')">Tổng hợp tình hình chi trả
                                        lương (Mẫu tổng hợp chi tiết đơn vị)</a></li>
                                <li><a href="#" data-target="#thoaidutoan-huyen-modal" data-toggle="modal"
                                        onclick="dutoanluong_huyen('{{ '/bao_cao/bang_luong/tinh/dutoanluongCR' }}')">Dự toán lương 
                                        </a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2a1_tt68' }}')">Báo cáo nhu cầu kinh phí thực hiện
                                        nghị định 72/2018/NĐ-CP (Mẫu 2a/1)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2a2_tt68' }}')">Báo cáo nhu cầu kinh phí thực hiện
                                        nghị định 72/2018/NĐ-CP (Mẫu 2a/2)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2b_tt68' }}')">Báo cáo tổng hợp quỹ trợ cấp tăng
                                        thêm
                                        của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a></li>

                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2c_tt68' }}')">Báo cáo nhu cầu kinh phí thực hiện
                                        BHTN
                                        theo nghị định 28/2015 (Mẫu 2c)</a></li>
                                <!--li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{ $furl . 'maubckpbhtn' }}')">Báo cáo nhu cầu kinh phí thực hiện bảo hiểm thất nghiệp theo nghị định 28/2015/NĐ-CP</a></li-->
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2d_tt68' }}')">Tổng hợp kinh phí tăng thêm để thực
                                        hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu 2d)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2đ_tt68' }}')">Báo cáo nguồn thực hiện CCTL tiết
                                        kiệm
                                        từ việc thực hiện tinh giảm biên chế (Mẫu 2đ)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2e_tt68' }}')">Báo cáo nguồn thực hiện CCTL tiết
                                        kiệm
                                        từ việc thay đổi cơ chế tự chủ (Mẫu 2e)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau2g_tt68' }}')">Báo cáo quỹ tiền lương, phụ cấp đối
                                        với lao động theo hợp đồng khu vực hành chính và đơn vị sự nghiệp (Mẫu 2g)</a></li>
                                <!--li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{ $furl . 'mau2h_tt67' }}')">Tổng hợp phụ cấp thu hút tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2h)</a></li-->
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau4a_tt68' }}')">Báo cáo nguồn kinh phí để thực hiện
                                        cải cách tiền lương (Mẫu 4a)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau4b_tt68' }}')">Tổng hợp nhu cầu, nguồn thực hiện
                                        nghị
                                        định 72/2018/NĐ-CP (Mẫu 4b)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal"
                                        onclick="baocao('{{ $furl . 'mau4b_tt68bs' }}')">Tổng hợp nhu cầu, nguồn thực hiện
                                        nghị định 72/2018/NĐ-CP (Mẫu 4b bổ sung)</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    {{-- <div class="form-group">
                        <label class="col-md-4 control-label">Đơn vị<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" id="donvi" name="donvi">
                                @foreach ($model_donvi as $donvi)
                                    <option value="{{$donvi['madvbc']}}">{{$donvi->tendvbc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
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
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaidutoan_huyen', 'class'=>'form-horizontal form-validate']) !!}
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
                            {!! Form::select('namns',getNam(),date('Y'),array('id' => 'namns', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân loại<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('phanloai',$a_phanloai,date('Y'),array('id' => 'phanloai', 'class' => 'form-control select2me'))!!}
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script type="text/javascript">
        function baocao(url) {
            $('#urlbc').val(url);
            $('#urlbcluong').val(url);
        }
        window.onsubmit = function() {
            document.thoaibc.action = get_action();
            document.thoaichitra_khoi_moi.action = get_action();
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
            $('#thoaichitra_khoi_moi').attr('action', url);
            $('#thoaichitra_huyen_moi').attr('action', url);
            $('#thoaichitra_huyen_ct').attr('action', url);
        }

        function dutoanluong_huyen(url){
            $('#thoaidutoan_huyen').attr('action',url);
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
