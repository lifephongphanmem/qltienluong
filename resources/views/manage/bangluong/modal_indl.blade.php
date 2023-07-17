<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/06/2019
 * Time: 10:32 AM
 */
?>

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
                                <a href="#tab_5" data-toggle="tab" aria-expanded="true">
                                    Mẫu bảng lương </a>
                            </li>
                            <li class="">
                                <a href="#tab_4" data-toggle="tab" aria-expanded="false">
                                    Mẫu Khánh Hòa </a>
                            </li>
                            <li class="">
                                <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                                    Mẫu Lạng Sơn & Cao Bằng </a>
                            </li>
                            <li class="">
                                <a href="#tab_3" data-toggle="tab" aria-expanded="false">
                                    Mẫu Lai Châu </a>
                            </li>
                            <li class="">
                                <a href="#tab_vn" data-toggle="tab" aria-expanded="false">
                                    Mẫu Vạn Ninh </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Mẫu bảng lương chung -->
                            <div class="tab-pane active" id="tab_5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm09nd11('/chuc_nang/bang_luong/mau09nd11')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán cho đối tượng thụ hưởng (NĐ11/2020/NĐ-CP)</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/mautt107')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau01')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT185/2010/TT-BTC)</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/dangkyluong')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng đăng ký lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng đăng ký lương mẫu C02-HD</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inds('/chuc_nang/bang_luong/mauds')" class="btn btn-default btn-xs mbs"
                                                    title="Danh sách chi trả cá nhân" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách chi trả cá nhân - mẫu 01</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inds('/chuc_nang/bang_luong/mauds_m2')" class="btn btn-default btn-xs mbs"
                                                    title="Danh sách chi trả cá nhân" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách chi trả cá nhân - mẫu 02</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
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
                            <div class="tab-pane" id="tab_4">
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
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/mautt107_m4')" class="btn btn-default btn-xs mbs"
                                                    title="" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC) - mẫu CR</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107_pb('/chuc_nang/bang_luong/mautt107_pb')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - theo khối, tổ công tác</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107('/chuc_nang/bang_luong/maumtm')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương theo mục, tiểu mục" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán tiền lương theo mục, tiểu mục</button>
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
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblpc('/chuc_nang/bang_luong/maublpc')" class="btn btn-default btn-xs mbs"
                                                    data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán tiền lương, phụ cấp</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="indbhdnd('/chuc_nang/bang_luong/maudbhdnd')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng thanh toán phụ cấp ĐBHDND" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp ĐBHDND</button>
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="indbhdnd('/chuc_nang/bang_luong/maubchd')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng thanh toán phụ cấp BCH Đảng Ủy" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp BCH Đảng Ủy</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inqs('/chuc_nang/bang_luong/mauqs')" class="btn btn-default btn-xs mbs"
                                                    data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp quân sự</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_cd" href="" onclick="incd()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ trung tâm học tập cấp cộng đồng</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_mc" href="" onclick="inmc()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp trách nhiệm</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_truc" href="" onclick="intruc()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp trực</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a id="in_tn" href="" onclick="intn()" style="border-width: 0px;margin-left: 5px" target="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp đội tình nguyện</a>
                                        </div>
                                    </div>
                                </div> --}}
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inC02('/chuc_nang/bang_luong/mauC02_KH')" class="btn btn-default btn-xs mbs"
                                            title="Bảng lương thêm cột giảm trừ lương, phần trăm vượt khung" data-target="#mauC02-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02 - HD</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="in09('/chuc_nang/bang_luong/mau09_KH')" class="btn btn-default btn-xs mbs"
                                            title="Bảng thanh toán cho đối tượng thụ hưởng" data-target="#mau09-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng thanh toán cho đối tượng thụ hưởng</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblcbcc_mauxa('/chuc_nang/bang_luong/maublcbct')" class="btn btn-default btn-xs mbs"
                                            title="Bảng lương cán bộ công chức, chuyên trách" data-target="#maucbct-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng lương cán bộ công chức, chuyên trách</button>

                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblcbkct('/chuc_nang/bang_luong/maublcbkct')" class="btn btn-default btn-xs mbs"
                                            title="Bảng lương cán bộ không chuyên trách xã" data-target="#maucbkct-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu xã, phường, thị trấn</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblcbkct_thon('/chuc_nang/bang_luong/maublcbkct')" class="btn btn-default btn-xs mbs"
                                            title="Bảng lương cán bộ không chuyên trách cấp thôn" data-target="#maucbkct-thon-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng lương cán bộ không chuyên trách cấp thôn</button>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblcblldq('/chuc_nang/bang_luong/maublcbkct')" class="btn btn-default btn-xs mbs"
                                            title="Bảng thanh toán phụ cấp trách nhiệm quản lý đơn vị LLDQ" data-target="#maucblldq-modal" data-toggle="modal">
                                        <i class="fa fa-print"></i>&nbsp; Bảng thanh toán phụ cấp trách nhiệm quản lý đơn vị LLDQ</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <!-- Mẫu bảng lương lạng sơn + cao bằng -->
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau03')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu Lạng Sơn - Mẫu 01</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau04')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu Lạng Sơn - Mẫu 02</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107_m2('/chuc_nang/bang_luong/mautt107_m5')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương theo TT107/2017/TT-BTC - làm tròn số tiền thực nhận" data-target="#mautt107_m2-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (làm tròn số tiền thực nhận)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau05')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu Cao Bằng</button>
                                        </div>
                                    </div>
                                    {{--<div class="col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau04')" class="btn btn-default btn-xs mbs"--}}
                                    {{--title="Bảng lương của cán bộ theo nhóm/tổ công tác" data-target="#mau1-modal" data-toggle="modal">--}}
                                    {{--<i class="fa fa-print"></i>&nbsp; Bảng lương mẫu 4</button>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm6()" class="btn btn-default btn-xs mbs"
                                                    data-target="#mau6-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu mục tiểu mục</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau07')"
                                                    class="btn btn-default btn-xs mbs" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu hệ số - số tiền</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mauquy')"
                                                    class="btn btn-default btn-xs mbs" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu các quỹ trích nộp</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107_pb('/chuc_nang/bang_luong/mautt107_pb_m2')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu theo khối, tổ công tác</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/dstangluong')" class="btn btn-default btn-xs mbs"
                                                    title="Căn cứ theo bảng lương tháng trước (cùng nguồn kinh phí)" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ tăng lương</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/dsgiamluong')" class="btn btn-default btn-xs mbs"
                                                    title="Căn cứ theo bảng lương tháng trước (cùng nguồn kinh phí)" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách cán bộ giảm lương</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inds('/chuc_nang/bang_luong/mauds_m2')" class="btn btn-default btn-xs mbs"
                                                    title="Danh sách chi trả cá nhân" data-target="#mauds-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp; Danh sách chi trả cá nhân</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Mẫu bảng lương Lai Châu -->
                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mautt107_lc')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp;Bảng lương mẫu C02-HD</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mautt107_lc_xp')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp;Bảng lương mẫu C02-HD - mẫu xã, phường, thị trấn</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblmtt107_pb('/chuc_nang/bang_luong/mautt107_lc_pb')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ theo mẫu C02-HD" data-target="#mautt107-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i>&nbsp;Bảng lương mẫu C02-HD - theo khối, tổ công tác</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mẫu bảng lương Vạn Ninh -->
                            <div class="tab-pane" id="tab_vn">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau09_vn_hc')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i> Bảng thanh toán cho đối tượng thụ hưởng (Mẫu đơn vị hành chính)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau09_vn_ck_bc')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i> Bảng thanh toán chuyển khoản cho công chức (Mẫu xã, phường, thị trấn)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau09_vn_ck_kct')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i> Bảng thanh toán chuyển khoản cho cán bộ không chuyên trách (Mẫu xã, phường, thị trấn)</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" style="border-width: 0px" onclick="inblm1('/chuc_nang/bang_luong/mau09_vn_tm')" class="btn btn-default btn-xs mbs"
                                                    title="Bảng lương của cán bộ thiết kế theo mẫu đặc thù" data-target="#mau1-modal" data-toggle="modal">
                                                <i class="fa fa-print"></i> Bảng thanh toán tiền mặt (Mẫu xã, phường, thị trấn)</button>
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
                        <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs" onclick="inblmnd11_th('/chuc_nang/bang_luong/mau09nd11_th')"
                                data-toggle="modal" data-target="#mautt107_th-modal">
                            <i class="fa fa-print"></i>&nbsp;Bảng thanh toán cho đối tượng thụ hưởng (Theo phân loại)</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs" onclick="inblmnd11_th('/chuc_nang/bang_luong/mau09nd11_th_m2')"
                                data-toggle="modal" data-target="#mautt107_th-modal">
                            <i class="fa fa-print"></i>&nbsp;Bảng thanh toán cho đối tượng thụ hưởng (Theo cá nhân)</button>
                    </div>
                </div>

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

<!--Modal thông tin tùy chọn in truy lĩnh lương -->
<div id="inbl_tl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-lg modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="hd-inbl_tl" class="modal-title">In bảng lương truy lĩnh</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                onclick="inblmtt107_tl('/chuc_nang/bang_luong/mautt107')"
                                data-toggle="modal" data-target="#mautt107-modal-tl"
                                title="Bảng truy lĩnh lương của cán bộ theo mẫu C02-HD">
                            <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC) - Mẫu 01</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-toggle="modal" data-target="#mautt107-modal-tl"
                                title="Bảng truy lĩnh lương - thêm thời gian truy lĩnh"
                                onclick="inblmtt107_tl('/chuc_nang/bang_luong/mautruylinh')">
                            <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - Mẫu 02</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-toggle="modal" data-target="#mautt107-modal-tl"
                                title="Bảng truy lĩnh lương - lấy nội dung truy lĩnh"
                                onclick="inblmtt107_tl('/chuc_nang/bang_luong/mautruylinh_m2')">
                            <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - Mẫu 03</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>

<!--Modal thông tin tùy chọn in bảng chi lương khác -->
<div id="inbl_khac-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-lg modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="hd-inbl_khac" class="modal-title">In bảng chi trả các loại phụ cấp</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="" onclick="setUrl(this,'/chuc_nang/bang_luong/mautruc')" style="border-width: 0px;margin-left: 5px" target="_blank">
                            <i class="fa fa-print"></i>&nbsp;Bảng thanh toán phụ cấp (Mẫu 01)</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <a href="m2" onclick="setUrl(this,'/chuc_nang/bang_luong/mautruc_m2')"  style="border-width: 0px;margin-left: 5px" target="_blank">
                            <i class="fa fa-print"></i>&nbsp;Bảng thanh toán phụ cấp (Mẫu 02)</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="m2" onclick="setUrl(this,'/chuc_nang/bang_luong/mauctphi')"  style="border-width: 0px;margin-left: 5px" target="_blank">
                            <i class="fa fa-print"></i>&nbsp;Bảng thanh toán công tác phí</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
        </div>
    </div>
</div>
