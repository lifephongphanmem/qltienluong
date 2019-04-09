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
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
            $('#printf_mautt107_th').find("[id^='manguonkp']").select2();
            //$("#manguonkp_th").select2();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH CHI TRẢ LƯƠNG CỦA ĐƠN VỊ</div>
                    <div class="actions">
                        @if($inputs['thaotac'])
                            <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới bảng lương</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="add_truylinh()"><i class="fa fa-plus"></i>&nbsp;Thêm mới bảng truy lĩnh</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="add_truc()"><i class="fa fa-plus"></i>&nbsp;Thêm mới bảng trực</button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-2 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thangct',getThang(),$inputs['thang'],array('id' => 'thangct', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('namct',getNam(),$inputs['nam'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                            <div class="col-md-offset-1 col-md-2">
                            <button type="button" onclick="inbl_th('{{$inputs['thang']}}','{{$inputs['nam']}}')" class="btn btn-default mbs">
                                <i class="fa fa-print"></i>&nbsp; In bảng lương tổng hợp</button>
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Nguồn kinh phí</th>
                                <th class="text-center">Nội dung bảng lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <?php $i=1;?>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td>{{$value->tenphanloai}}</td>
                                        <td>{{$value->tennguonkp}}</td>
                                        <td>{{$value->noidung}}</td>
                                        <td>
                                            @if($value->thaotac)
                                                <button type="button" onclick="edit('{{$value->mabl}}','{{$value->phanloai}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                                <a href="{{url($inputs['furl'].'bang_luong?mabl='.$value->mabl.'&mapb=')}}" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>

                                                @if($value->phanloai == 'BANGLUONG')
                                                    <button onclick="capnhat('{{$value->mabl}}')" class="btn btn-default btn-xs mbs" data-target="#capnhat-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-th-list"></i>&nbsp; Cập nhật lương</button>

                                                    <!--button onclick="tanggiam('{{$value->mabl}}')" class="btn btn-default btn-xs mbs" data-target="#tanggiam-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-th-list"></i>&nbsp; Tăng/Giảm lương</button-->
                                                @endif

                                                <button type="button" onclick="cfDel('{{$inputs['furl'].'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                            <button type="button" onclick="inbl('{{$value->mabl}}','{{$value->thang}}','{{$value->nam}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chi tiết -->
    {!! Form::open(['url'=>'/chuc_nang/bang_luong/store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng lương cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label"> Nội dung</label>
                            {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}
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

    <script>
        function capnhat(mabl){
            $('#mabl_capnhat').val(mabl);
        }

        function tanggiam(mabl){
            $('#mabl_tg').val(mabl);
        }
    </script>
    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="inbl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In bảng lương</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab" aria-expanded="true">
                                    Mẫu bảng lương </a>
                            </li>
                            <li class="">
                                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                                    Mẫu bảng lương - Khánh Hòa </a>
                            </li>
                            <li class="">
                                <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                                    Mẫu bảng lương - Lạng Sơn & Cao Bằng </a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <!-- Mẫu bảng lương chung -->
                            <div class="tab-pane active" id="tab_0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/mautt107')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau01')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT185/2010/TT-BTC)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inds('/chuc_nang/bang_luong/mauds')" class="btn btn-default btn-xs mbs"
                                                    title="Danh sách chi trả cá nhân" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách chi trả cá nhân</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/maubh')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng tính bảo hiểm phải nộp của cán bộ" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng tính bảo hiểm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mẫu bảng lương khánh hòa -->
                            <div class="tab-pane" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/mautt107_m2')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương thêm cột giảm trừ lương, phần trăm vượt khung" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107_pb('/chuc_nang/bang_luong/mautt107_pb')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - theo khối, tổ công tác</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/mautt107_m3')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương thêm cột giảm trừ lương, phần trăm vượt khung" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - mẫu xã, phường, thị trấn</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_thpl" href="" onclick="inthpl()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp lương theo phân loại công tác</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_thpc" href="" onclick="inthpc()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng tổng hợp phụ cấp, trợ cấp</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblpc('/chuc_nang/bang_luong/maublpc')" class="btn btn-default btn-xs mbs"
                                                    data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán tiền lương, phụ cấp</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="indbhdnd('/chuc_nang/bang_luong/maudbhdnd')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng thanh toán phụ cấp ĐBHDND" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp ĐBHDND</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="indbhdnd('/chuc_nang/bang_luong/maubchd')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng thanh toán phụ cấp BCH Đảng Ủy" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp BCH Đảng Ủy</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inqs('/chuc_nang/bang_luong/mauqs')" class="btn btn-default btn-xs mbs"
                                                    data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp quân sự</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_cd" href="" onclick="incd()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ trung tâm học tập cấp cộng đồng</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_mc" href="" onclick="inmc()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp trách nhiệm</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_truc" href="" onclick="intruc()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp trực</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_tn" href="" onclick="intn()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp đội tình nguyện</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mẫu bảng lương lạng sơn + cao bằng -->
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau03')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 3</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau04')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo nhóm/tổ công tác" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 4</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau05')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 5</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm6()" class="btn btn-default btn-xs mbs"
                                                    data-target="#mau6-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 6</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau07')"
                                                    class="btn btn-default btn-xs mbs" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 7</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau08')"
                                                    class="btn btn-default btn-xs mbs" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 8</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>



                <input type="hidden" id="nam_in" name="nam_in"/>
                <input type="hidden" id="thang_in" name="thang_in"/>
                <input type="hidden" id="mabl_in" name="mabl_in"/>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>

    <!--Modal thông tin tùy chọn in tổng hợp bảng lương -->
    <div id="inbl_th-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In bảng lương tổng hợp</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs" onclick="inblmtt107_th('/chuc_nang/bang_luong/mautt107_th')"
                                    data-toggle="modal" data-target="#mautt107_th-modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC) - Mẫu 01</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-toggle="modal" data-target="#mautt107_th-modal"
                                title="Bảng lương thêm cột giảm trừ lương, phần trăm vượt khung"
                                onclick="inblmtt107_th('/chuc_nang/bang_luong/mautt107_th_m2')">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - Mẫu 02</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                    data-toggle="modal" data-target="#mautt107_th-modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD hệ số phụ cấp hiển thị số tiền"
                                    onclick="inblmtt107_th_pb('/chuc_nang/bang_luong/mautt107_pb_th')">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - theo khối, tổ công tác</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                    data-toggle="modal" data-target="#mautt107_th-modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD"
                                    onclick="inblmtt107_th('/chuc_nang/bang_luong/mau185_th')">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT185/2010/TT-BTC)</button>
                        </div>
                    </div>

                    <!--div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-toggle="modal" data-target="#mau7_th-modal">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 7</button>
                        </div>
                    </div-->
                </div>

                <!--div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                    data-toggle="modal" data-target="#mauds_th-modal">
                                <i class="fa fa-print"></i>&nbsp; Danh sách chi trả cá nhân</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                    data-toggle="modal" data-target="#maubh_th-modal">
                                <i class="fa fa-print"></i>&nbsp; Bảo hiểm </button>
                        </div>
                    </div>
                </div-->
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>

    @include('templates.modal_printf_luong')
    @include('templates.modal_printf_th_luong')
    <script>
        function getLink(){
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{$inputs['furl']}}'+'chi_tra?thang='+thang +'&nam='+nam;
        }
        $(function(){

            $('#thangct').change(function(){
                window.location.href = getLink();
            });

            $('#namct').change(function(){
                window.location.href = getLink();
            });

            $('#manguonkp').change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{$inputs['furl_ajax']}}' + 'get_nguonkp',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        manguonkp: $('#manguonkp').val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#luongcoban').prop('readonly',false);
                        $('#luongcoban').val(data['luongcb']);
                        $('#dinhmuc').val(data['dinhmuc']);
                        $('#luongcoban').prop('readonly',data['dinhmuc']);
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi!');
                    }
                });
            });
        })

        function add(){
            var dm = '{{$inputs['dinhmuc']}}';
            $('#noidung').val('');
            //$('#phantramhuong').val(100);
            $('#phanloai').val('BANGLUONG');
            $('#mabl').val('');
            $('#id_ct').val(0);
            if(dm == 1){//do khi đm = 0 (false) ct set = "readonly" ~ readonly = true =>sai
                $('#luongcoban').prop('readonly',true);
            }

            $('#chitiet-modal').modal('show');
        }

        function add_truylinh(){
            $('#phanloai_truylinh').val('TRUYLINH');
            $('#noidung_truylinh').val('');
            $('#mabl_truylinh').val('');
            $('#truylinh-modal').modal('show');
        }

        function add_truc(){
            $('#phanloai_truc').val('TRUC');
            $('#noidung_truc').val('');
            $('#mabl_truc').val('');
            $('#truc-modal').modal('show');
        }

        function edit(mabl,phanloai){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if(phanloai == 'TRUYLINH'){
                $.ajax({
                    url: '{{$inputs['furl_ajax']}}' + 'get',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        mabl: mabl
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#thang_truylinh').val(data.thang);
                        $('#nam_truylinh').val(data.nam);
                        $('#noidung_truylinh').val(data.noidung);
                        //$('#manguonkp_truylinh').val(data.manguonkp);
                        //$('#luongcoban_truylinh').val(data.luongcoban);
                        $('#mabl_truylinh').val(data.mabl);
                        $('#phanloai_truylinh').val(data.phanloai);
                        $('#ngaylap_truylinh').val(data.ngaylap);
                        $('#nguoilap_truylinh').val(data.nguoilap);
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi!');
                    }
                });
                $('#truylinh-modal').modal('show');
            }else{
                $.ajax({
                    url: '{{$inputs['furl_ajax']}}' + 'get',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        mabl: mabl
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#thang').val(data.thang);
                        $('#nam').val(data.nam);
                        $('#noidung').val(data.noidung);
                        $('#manguonkp').val(data.manguonkp);
                        $('#phantramhuong').val(data.phantramhuong);
                        $('#luongcoban').val(data.luongcoban);
                        $('#mabl').val(data.mabl);
                        $('#phanloai').val(data.phanloai);
                        $('#ngaylap').val(data.ngaylap);
                        $('#nguoilap').val(data.nguoilap);
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi!');
                    }
                });
                $('#chitiet-modal').modal('show');
            }

        }

        function inbl(mabl,thang,nam){
            document.getElementById("hd-inbl").innerHTML="In bảng lương tháng " + thang + ' năm ' + nam;
            $("#mabl_in").val(mabl);
            $("#thang_in").val(thang);
            $("#nam_in").val(nam);
            $('#inbl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function inbl_th(mabl,thang,nam){
            //document.getElementById("hd-inbl").innerHTML="In bảng lương tháng " + thang + ' năm ' + nam;
            $('#inbl_th-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function incd(){
            $("#in_cd").attr("href", '/chuc_nang/bang_luong/maucd?mabl=' + $('#mabl_in').val());
        }

        function inmc(){
            $("#in_mc").attr("href", '/chuc_nang/bang_luong/maumc?mabl=' + $('#mabl_in').val());
        }

        function intruc(){
            $("#in_truc").attr("href", '/chuc_nang/bang_luong/mautruc?mabl=' + $('#mabl_in').val());
        }

        function intn(){
            $("#in_tn").attr("href", '/chuc_nang/bang_luong/mautinhnguyen?mabl=' + $('#mabl_in').val());
        }

        function inthpc(){
            $("#in_thpc").attr("href", '/chuc_nang/bang_luong/mauthpc?mabl=' + $('#mabl_in').val());
        }

        function inthpl(){
            $("#in_thpl").attr("href", '/chuc_nang/bang_luong/mauthpl?mabl=' + $('#mabl_in').val());
        }
        $(function(){
            $('#create_bangluong :submit').click(function(){
                var ok = true, message='';
                var thang=$('#thang').val();
                var nam=$('#nam').val();

                if(thang==null){
                    ok=false;
                    message +='Tháng bảng lương không được bỏ trống. \n';
                }
                if(nam==null){
                    ok=false;
                    message +='Năm bảng lương không được bỏ trống. \n';
                }

                //Kết quả
                if ( ok == false){
                    toastr.error(message,"Lỗi!");
                    $("form").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("form").unbind('submit').submit();
                }
            });
        });
    </script>
    @include('includes.modal.delete')
@stop