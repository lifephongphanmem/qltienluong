<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/06/2019
 * Time: 10:32 AM
 */
        ?>

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
<div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label"> Nội dung</label>
                        {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'1'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Lĩnh vực công tác</label>
                        {!!Form::select('linhvuchoatdong',$m_linhvuc ,session('admin')->maphanloai == 'KVXP' ?'QLNN':session('admin')->linhvuchoatdong , array('id' => 'linhvuchoatdong','class' => 'form-control select2me'))!!}
                    </div>

                    <div class="col-md-4">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!!Form::select('manguonkp',$m_nguonkp, $inputs['manguonkp'], array('id' => 'manguonkp','class' => 'form-control'))!!}
                    </div>

                    <div class="col-md-4">
                        <label class="control-label">Mức lương cơ bản</label>
                        {!!Form::text('luongcoban', $inputs['luongcb'], array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Ngày lập bảng lương</label>
                        <input type="date" name="ngaylap" id="ngaylap" class="form-control" value="{{date('Y-m-d')}}"/>

                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Người lập bảng lương</label>
                        {!!Form::text('nguoilap', session('admin')->nguoilapbieu, array('id' => 'nguoilap','class' => 'form-control'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Không bao gồm các phụ cấp</label>
                        {!! Form::select('phucaploaitru[]',$a_phucap,null,array('id' => 'phucaploaitru','class' => 'form-control select2me','multiple'=>'multiple')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Các phụ cấp lưu theo số tiền</label>
                        {!! Form::select('phucapluusotien[]',$a_phucaplst,null,array('id' => 'phucapluusotien','class' => 'form-control select2me','multiple'=>'multiple')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                        <input name="capnhatnangluong" id="capnhatnangluong" type="checkbox">
                        <label for="capnhatnangluong">Cập nhật quá trình nâng lương của cán bộ</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="trungbangluong" id="trungbangluong" type="checkbox">
                        <label for="trungbangluong">Tạo nhiều bảng lương trong cùng nguồn kinh phí</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <input name="thuetncn" id="thuetncn" type="checkbox" checked>
                        <label for="thuetncn">Tính thuế thu nhập cá nhân</label>
                    </div>
                </div>

                <input type="hidden" id="dinhmuc" name="dinhmuc" value="{{$inputs['dinhmuc']}}"/>
                <input type="hidden" id="thang" name="thang" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam" name="nam" value="{{$inputs['nam']}}"/>
                <input type="hidden" id="phantramhuong" name="phantramhuong" value="100"/>
                <input type="hidden" id="id_ct" name="id_ct"/>
                <input type="hidden" id="mabl" name="mabl"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" onclick="disable_btn(this)" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store_truylinh','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong_truylinh']) !!}
<div id="truylinh-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng truy lĩnh lương</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label"> Nội dung</label>
                        {!! Form::textarea('noidung_truylinh',null,array('id' => 'noidung_truylinh', 'class' => 'form-control','rows'=>'3'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Lĩnh vực công tác </label>
                        {!! Form::select('linhvuchoatdong_truylinh',$m_linhvuc,session('admin')->linhvuchoatdong ,array('id' => 'linhvuchoatdong_truylinh','class' => 'form-control select2me')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Ngày lập bảng lương</label>
                        <input type="date" name="ngaylap_truylinh" id="ngaylap_truylinh" class="form-control" value="{{date('Y-m-d')}}"/>

                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Người lập bảng lương</label>
                        {!!Form::text('nguoilap_truylinh', session('admin')->nguoilapbieu, array('id' => 'nguoilap_truylinh','class' => 'form-control'))!!}
                    </div>
                </div>

                <input type="hidden" id="thang_truylinh" name="thang_truylinh" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam_truylinh" name="nam_truylinh" value="{{$inputs['nam']}}"/>
                <input type="hidden" id="mabl_truylinh" name="mabl_truylinh"/>
                <input type="hidden" id="phanloai_truylinh" name="phanloai_truylinh"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" onclick="disable_btn(this)" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store_truc','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong_truc']) !!}
<div id="truc-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng lương trực cán bộ</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label"> Nội dung</label>
                        {!! Form::textarea('noidung_truc',null,array('id' => 'noidung_truc', 'class' => 'form-control','rows'=>'3'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!!Form::select('manguonkp_truc',$m_nguonkp, $inputs['manguonkp'], array('id' => 'manguonkp_truc','class' => 'form-control'))!!}
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Lĩnh vực công tác </label>
                        {!! Form::select('linhvuchoatdong_truc',$m_linhvuc,session('admin')->maphanloai == 'KVXP' ?'QLNN':null ,array('id' => 'linhvuchoatdong_truc','class' => 'form-control select2me')) !!}
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <label class="control-label">Mức lương cơ bản</label>
                        {!!Form::text('luongcoban_truc', $inputs['luongcb'], array('id' => 'luongcoban_truc','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Ngày lập bảng lương</label>
                        <input type="date" name="ngaylap_truc" id="ngaylap_truc" class="form-control" value="{{date('Y-m-d')}}"/>

                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Người lập bảng lương</label>
                        {!!Form::text('nguoilap_truc', session('admin')->nguoilapbieu, array('id' => 'nguoilap_truc','class' => 'form-control'))!!}
                    </div>
                </div>
                <input type="hidden" id="thang_truc" name="thang_truc" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam_truc" name="nam_truc" value="{{$inputs['nam']}}"/>
                <input type="hidden" id="mabl_truc" name="mabl_truc"/>
                <input type="hidden" id="phanloai_truc" name="phanloai_truc"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" onclick="disable_btn(this)" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store_ctp','method'=>'post' , 'files'=>true, 'id' => 'create_ctp']) !!}
<div id="ctphi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi trả công tác phí của cán bộ</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label"> Nội dung</label>
                        {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Ngày lập</label>
                        <input type="date" name="ngaylap" id="ngaylap" class="form-control" value="{{date('Y-m-d')}}"/>

                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Người lập</label>
                        {!!Form::text('nguoilap', session('admin')->nguoilapbieu, array('id' => 'nguoilap','class' => 'form-control'))!!}
                    </div>
                </div>
                <input type="hidden" id="thang" name="thang" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam" name="nam" value="{{$inputs['nam']}}"/>
                <input type="hidden" id="mabl" name="mabl"/>
                <input type="hidden" id="phanloai" name="phanloai"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>'/chuc_nang/bang_luong/cap_nhat','method'=>'get' , 'files'=>true, 'id' => 'frmcapnhat']) !!}
<div id="capnhat-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Đồng ý cập nhật lại bảng lương ?</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><b>Chi tiết bảng lương sẽ được cập nhật lại theo thông tin cán bộ mới nhất. Bạn có chắc chắn muốn cập nhật ?</b></label>
                </div>
            </div>

            <input type="hidden" id="mabl_capnhat" name="mabl_capnhat"/>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--Modal thông tin tùy chọn in bảng lương -->
<div id="capnhat_nkp-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-lg modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="hd-inbl" class="modal-title">Cập nhật thông tin bảng lương</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab" aria-expanded="true">
                                    Nguồn kinh phí </a>
                            </li>
                            <li>
                                <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                    Khen thưởng, giảm trừ </a>
                            </li>
                            <!--li>
                                <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                    Sự nghiệp </a>
                            </li>

                            <li>
                                <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                    Nguồn kinh phí </a>
                            </li-->
                        </ul>
                        <div class="tab-content">
                            <!-- phân loại công tác -->
                            <div class="tab-pane active" id="tab_0">
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption"></div>
                                        <div class="tools"></div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        {!! Form::open(['url'=>'/chuc_nang/bang_luong/cap_nhat_nkp','method'=>'get', 'files'=>true, 'id' => 'frmcapnhat_nkp']) !!}
                                        <div class="form-body">
                                            <div class="form-horizontal">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="control-label">Nguồn kinh phí</label>
                                                            {!!Form::select('manguonkp',$m_nguonkp, null, array('id' => 'manguonkp','class' => 'form-control'))!!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="mabl" name="mabl"/>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row text-center">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn default">Hoàn thành</button>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày công lam việc -->
                            <div class="tab-pane" id="tab_1">
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption"></div>
                                        <div class="tools"></div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                    {!! Form::open(['url'=>'/chuc_nang/bang_luong/updatect_khenthuong', 'method' => 'POST', 'id' => 'frm_khenthuong', 'class'=>'horizontal-form']) !!}
                                        <div class="form-body">
                                            <div class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="control-label">Phân loại công tác</label>
                                                        <select class="form-control select2me" name="mact[]" id="mact" multiple required="required">
                                                            <option selected value="ALL">Tất cả phân loại công tác</option>
                                                            @foreach($model_nhomct as $kieuct)
                                                                <optgroup label="{{$kieuct->tencongtac}}">
                                                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                                    @foreach($mode_ct as $ct)
                                                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="control-label">Khen thưởng</label>
                                                        {!!Form::text('tienthuong', null, array('id' => 'tienthuong','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="control-label">Giảm trừ lương </label>
                                                        {!!Form::text('giaml', null, array('id' => 'giaml','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>
                                                <input type="hidden" id="mabl" name="mabl"/>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row text-center">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn default">Hoàn thành</button>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>


{!! Form::open(['url'=>'/chuc_nang/bang_luong/cap_nhat_nkp','method'=>'get', 'files'=>true, 'id' => 'frmcapnhat_nkp']) !!}
<div id="capnhat_nkp-modal1" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Đồng ý cập nhật lại nguồn kinh phí ?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Nguồn kinh phí</label>
                        {!!Form::select('manguonkp',$m_nguonkp, null, array('id' => 'manguonkp','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>

            <input type="hidden" name="mabl"/>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

<!--
{!! Form::open(['url'=>'/chuc_nang/bang_luong/tang_giam','method'=>'get' , 'files'=>true, 'id' => 'create_bangluong_truylinh']) !!}
<div id="tanggiam-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <form id="frmtanggiam" method="GET" action="#" accept-charset="UTF-8">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin tăng / giảm lương cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Khối/Tổ công tác</label>
                                <select name="mapb_tg" id="mapb_tg" class="form-control select2me">
                                    @foreach(getPhongBan(true) as $key=>$val)
                                        <option value="{{$key}}">{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Chức vụ</label>
                                {!!Form::select('macvcq_tg',getChucVuCQ(true), null, array('id' => 'macvcq_tg','class' => 'form-control select2me'))!!}
                            </div>

                            <div class="col-md-12">
                                <label class="control-label">Phân loại công tác</label>
                                <select class="form-control select2me" name="mact_tg" id="mact_tg">
                                    <option value="">-- Tất cả các phân loại công tác --</option>
                                    @foreach($model_nhomct as $kieuct)
                                        <optgroup label="{{$kieuct->tencongtac}}">
                                            <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                            @foreach($mode_ct as $ct)
                                                <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Phân loại</label>
                                {!!Form::select('phanloai_tg', getTangGiamLuong(), 'TANG',array('id' => 'phanloai_tg','class' => 'form-control select2me'))!!}
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Tùy chọn</label>
                                {!!Form::select('kieutanggiam_tg', getKieuTangGiamLuong(), 'SOTIEN',array('id' => 'kieutanggiam_tg','class' => 'form-control select2me'))!!}
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">Số tiền / Ngày công</label>
                                {!!Form::text('sotien_tg', 0, array('id' => 'sotien_tg','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="mabl_tg" name="mabl_tg"/>
                </div>


                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </form>
</div>
{!! Form::close() !!}
-->

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store_trichnop','method'=>'post' , 'files'=>true, 'id' => 'create_trichnop']) !!}
<div id="trichnop-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content modal-lg">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin trích nộp lương</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label"> Nội dung</label>
                        {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'1'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label"> Tên quỹ trích nộp <span class="require">*</span></label>
                        {!! Form::text('tenquy',null,array('id' => 'tenquy', 'class' => 'form-control'))!!}
                    </div>

                    <div class="col-md-4">
                        <label class="control-label">Ngày lập</label>
                        <input type="date" name="ngaylap" id="ngaylap" class="form-control" value="{{date('Y-m-d')}}"/>
                    </div>

                    <div class="col-md-4">
                        <label class="control-label">Người lập</label>
                        {!!Form::text('nguoilap', session('admin')->nguoilapbieu, array('id' => 'nguoilap','class' => 'form-control'))!!}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2" style="padding-top: 6px;">
                        <div class="md-radio">
                            <input type="radio" id="pp_sotien" name="pptinh" class="md-radiobtn" checked value="sotien">
                            <label for="pp_sotien"><span class="inc"></span><span class="check"></span><span class="box"></span>
                                Số tiền</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::text('sotien',null,array('id' => 'sotien', 'class' => 'form-control','placeholder'=>'Số tiền trích nộp',
                            'data-mask'=>'fdecimal','title'=>'Số tiền trích nộp'))!!}
                    </div>

                    <div class="col-md-2" style="padding-top: 6px;">
                        <div class="md-radio">
                            <input type="radio" id="pp_phantram" name="pptinh" class="md-radiobtn form-control" value="phantram">
                            <label for="pp_phantram"><span class="inc"></span><span class="check"></span><span class="box"></span>
                                Phần trăm</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::text('phantram',null,array('id' => 'phantram', 'class' => 'form-control','placeholder'=>'Số % hưởng',
                        'data-mask'=>'fdecimal', 'readonly'=>'true','title'=>'Số phần trăm trích nộp'))!!}
                    </div>
                </div>

                <div class="row" style="padding-top: 15px;">
                    <div class="col-md-2" style="padding-top: 6px;">
                        <div class="md-radio">
                            <input type="radio" id="pp_ngaycong" name="pptinh" class="md-radiobtn form-control" value="ngaycong">
                            <label for="pp_ngaycong"><span class="inc"></span><span class="check"></span><span class="box"></span>
                                Ngày công</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        {!! Form::text('tongngaycong',session('admin')->songaycong,array('id' => 'tongngaycong', 'class' => 'form-control',
                            'placeholder'=>'Tổng số ngày công', 'data-mask'=>'fdecimal','readonly'=>'true','title'=>'Tổng số ngày công trong tháng'))!!}
                    </div>

                    <div class="col-md-3">
                        {!! Form::text('ngaycong',1,array('id' => 'ngaycong', 'class' => 'form-control','placeholder'=>'Ngày công',
                            'data-mask'=>'fdecimal','readonly'=>'true','title'=>'Số ngày công trích nộp'))!!}
                    </div>

                    <div class="col-md-4">
                        {!! Form::text('phantramtinh','100',array('id' => 'phantramtinh', 'class' => 'form-control','placeholder'=>'Phần trăm tính trích nộp',
                        'data-mask'=>'fdecimal','readonly'=>'true','title'=>'Phần trăm tính'))!!}
                    </div>
                </div>

                <hr style="margin-bottom: 5px;">

                <div class="row">
                    <div class="col-md-9">
                        <label class="control-label">Phụ cấp trích nộp</label>
                        {!! Form::select('phucap[]',$a_phucap_trichnop,'ALL',array('id' => 'phucap','class' => 'form-control select2me',
                        'multiple'=>'multiple','title'=>'Phụ cấp tính phần trăm')) !!}
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Làm tròn số tiền</label>
                        {!! Form::select('lamtron',getTronSo(),'0',array('id' => 'lamtron','class' => 'form-control select2me')) !!}
                    </div>
                </div>

                <input type="hidden" id="thang" name="thang" value="{{$inputs['thang']}}"/>
                <input type="hidden" id="nam" name="nam" value="{{$inputs['nam']}}"/>
                <input type="hidden" id="mabl_trichnop" name="mabl_trichnop"/>
                <input type="hidden" id="mabl" name="mabl"/>
                <input type="hidden" id="phanloai" name="phanloai"/>
                <input type="hidden" id="phanloai_pptinh" name="phanloai_pptinh" value="sotien"/>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" onclick="confirm_trichnop()" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
