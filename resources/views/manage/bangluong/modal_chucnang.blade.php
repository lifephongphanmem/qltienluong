<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/06/2019
 * Time: 10:32 AM
 */
        ?>

<div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed" id="tab_cre">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_0_cre" data-toggle="tab" aria-expanded="true">
                                        Thông tin chung </a>
                                </li>
                                <li class="" id="li_tab_1_cre">
                                    <a href="#tab_1_cre" data-toggle="tab" aria-expanded="false">
                                        Tạo bảng lương theo mẫu </a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <!-- Thông tin chung -->
                                <div class="tab-pane active" id="tab_0_cre">
                                    {!! Form::open(['url'=>'/chuc_nang/bang_luong/store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label"> Nội dung</label>
                                            {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'1'))!!}
                                        </div>
                                    </div>
                                    <!-- Phân loại đơn vị xa phường ko cần lĩnh vực hoạt động -->
                                    @if(session('admin')->maphanloai != 'KVXP')
                                        <label class="control-label">Lĩnh vực công tác </label>
                                        <select id="linhvuchoatdong" name="linhvuchoatdong" class="form-control">
                                            @foreach($m_linhvuc as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Nguồn kinh phí</label>
                                            {!!Form::select('manguonkp',$m_nguonkp, $inputs['manguonkp'], array('id' => 'manguonkp','class' => 'form-control'))!!}
                                        </div>

                                        <div class="col-md-6">
                                            <label class="control-label">Mức lương cơ bản</label>
                                            {!!Form::text('luongcoban', $inputs['luongcb'], array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Ngày lập bảng lương</label>
                                            <input type="date" name="ngaylap" id="ngaylap" class="form-control" value="{{date('Y-m-d')}}"/>

                                        </div>
                                        <div class="col-md-6">
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
                                        <div class="col-md-offset-3 col-md-9" style="margin-top: 15px">
                                            <input name="capnhatnangluong" id="capnhatnangluong" type="checkbox">Cập nhật quá trình nâng lương của cán bộ</label>
                                        </div>
                                    </div>


                                    <input type="hidden" id="dinhmuc" name="dinhmuc" value="{{$inputs['dinhmuc']}}"/>
                                    <input type="hidden" id="thang" name="thang" value="{{$inputs['thang']}}"/>
                                    <input type="hidden" id="nam" name="nam" value="{{$inputs['nam']}}"/>
                                    <input type="hidden" id="phantramhuong" name="phantramhuong" value="100"/>
                                    <input type="hidden" id="id_ct" name="id_ct"/>
                                    <input type="hidden" id="mabl" name="mabl"/>
                                </div>

                                <!-- Tùy chọn nâng cao -->
                                <div class="tab-pane" id="tab_1_cre">
                                    <table id="sample_4" class="table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">STT</th>
                                            <th class="text-center">Tháng</br>Năm</th>
                                            <th class="text-center">Nguồn kinh phí</th>
                                            <th class="text-center">Nội dung</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <?php $i=1;?>
                                        <tbody>
                                        @foreach($model_bl as $key=>$value)
                                            <tr>
                                                <td class="text-center">{{$i++}}</td>
                                                <td>{{$value->thang.'/'.$value->nam}}</td>
                                                <td>{{isset($m_nguonkp[$value->manguonkp]) ? $m_nguonkp[$value->manguonkp] : ''}}</td>
                                                <td>{{$value->noidung}}</td>
                                                <td>
                                                    <button type="button" onclick="taobl('{{$value->mabl}}')" class="btn btn-default btn-xs mbs">
                                                        <i class="fa fa-edit"></i>&nbsp; Chọn</button>

                                                    <a href="{{url($inputs['furl'].'bang_luong?mabl='.$value->mabl.'&mapb=')}}" class="btn btn-default btn-xs mbs" target="_blank">
                                                        <i class="fa fa-th-list"></i>&nbsp; Danh sách</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
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

                <!-- Phân loại đơn vị xa phường ko cần lĩnh vực hoạt động -->
                @if(session('admin')->maphanloai != 'KVXP')
                    <label class="control-label">Lĩnh vực công tác </label>
                    <select id="linhvuchoatdong_truylinh" name="linhvuchoatdong_truylinh" class="form-control">
                        @foreach($m_linhvuc as $key => $val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                @endif

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
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

{!! Form::open(['url'=>'/chuc_nang/bang_luong/store_truc','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong_truylinh']) !!}
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
                        {!! Form::textarea('noidung_truc',null,array('id' => 'noidung_truylinh', 'class' => 'form-control','rows'=>'3'))!!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Số ngày công</label>
                        {!!Form::text('songay_truc', date('t'), array('id' => 'songay_truc','class' => 'form-control'))!!}
                    </div>
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
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
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

{!! Form::open(['url'=>'/chuc_nang/bang_luong/cap_nhat','method'=>'get' , 'files'=>true, 'id' => 'create_bangluong_truylinh']) !!}
<div id="capnhat-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <form id="frmcapnhat" method="GET" action="#" accept-charset="UTF-8">
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
    </form>
</div>
{!! Form::close() !!}

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
