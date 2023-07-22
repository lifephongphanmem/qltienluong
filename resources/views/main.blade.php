<!DOCTYPE html>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>{{ $pageTitle }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="Phần mềm quản lý biên chế" />
    <meta content="" name="LifeSoft" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <script src="{{ url('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="{{ url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet') }}"
        type="text/css" />
    <link href="{{ url('assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css">
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="{{ url('assets/admin/pages/css/tasks.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet"
        type="text/css" />
    @yield('custom-style')
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="{{ url('assets/global/css/components-rounded.css') }}" id="style_components" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/global/css/plugins.css') }}"rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/layout4/css/layout.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/layout4/css/themes/light.css') }}" rel="stylesheet" type="text/css"
        id="style_color" />
    <link href="{{ url('assets/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="{{ url('images/LIFESOFT.png') }}" type="image/x-icon">
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->

<body
    class="page page-header-fixed page-footer-fixed page-sidebar-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="{{ url('') }}">
                    <img src="{{ url('images/LOGO_LIFE.png') }}" alt="logo" class="logo-default"
                        style="margin-top: 5px;">
                </a>
                <div class="menu-toggler sidebar-toggler">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse">
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->

            <!-- BEGIN PAGE TOP -->
            <div class="page-top">

                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown dropdown-user" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
                                <i class="fa fa-folder-open-o"></i>
                                <span class="username"><b>Trợ giúp</b></span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{ url('/thongtinhotro') }}" target="_blank"> Thông tin hỗ trợ</a>
                                </li>
                                @if (session('admin')->ipf1 != '')
                                    <li>
                                        <a href="{{ url('/data/huongdan/' . session('admin')->ipf1) }}"
                                            target="_blank">Tài liệu hướng dẫn</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
                                {{-- <img alt="" class="img-circle" src="{{url('/images/avatar/default-user.png')}}"/> --}}
                                <i class="fa icon-user"></i>
                                <span class="username">
                                    <b>{{ session('admin')->name }}</b> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                @if (session('admin')->chuyendoi != null)
                                    <li>
                                        <a href="{{ url('/don_vi_cd') }}">
                                            <i class="icon-users"></i> Chuyển đơn vị sử dụng</a>
                                    </li>
                                @endif

                                <li>
                                    <a href="{{ url('change-password') }}">
                                        <i class="icon-lock"></i> Đổi mật khẩu</a>
                                </li>
                                <li>
                                    <a href="{{ url('logout') }}">
                                        <i class="icon-key"></i> Đăng xuất </a>
                                </li>

                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END PAGE TOP -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse collapse">
                <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li class="start">
                        <a href="{{ url('') }}">
                            <i class="icon-home"></i>
                            <span class="title">Tổng quan</span>
                        </a>
                    </li>

                    @if (session('admin')->phanloaitaikhoan == 'SD')
                        @if (session('admin')->tinh == 'LAICHAU')
                            <li>
                                <a href="javascript:;">
                                    <i class="fa fa-wrench"></i>
                                    <span class="title">Nghiệp vụ</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-folder-open-o"></i> Quản lý cán bộ <span
                                                class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu" style="margin-left: 15px;">
                                            <li><a href="{{ url('/nghiep_vu/nhan_su/danh_sach') }}">Cán bộ đang công
                                                    tác</a></li>
                                            <li><a href="{{ url('/nghiep_vu/tam_ngung/danh_sach') }}">Cán bộ tạm
                                                    ngừng
                                                    theo dõi</a></li>
                                            <li><a href="{{ url('/nghiep_vu/da_nghi/danh_sach') }}">Cán bộ đã thôi
                                                    công tác</a></li>
                                            <li>
                                                <a
                                                    href="{{ url('/nghiep_vu/truy_linh/danh_sach?thang=ALL' . '&nam=' . date('Y')) }}"></i>Cán
                                                    bộ được truy lĩnh lương</a>
                                            </li>
                                            <li>
                                                <a
                                                    href="{{ url('/nghiep_vu/chi_tieu/danh_sach?namct=' . date('Y')) }}"><i
                                                        class="fa fa-caret-right"></i>Chỉ tiêu biên chế</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-folder-open-o"></i> Quản lý hồ sơ <span
                                                class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu" style="margin-left: 15px;">
                                            {{-- <li><a href="{{url('nghiep_vu/quan_ly/tai_lieu/maso=all')}}">Tài liệu kèm theo</a></li> --}}
                                            <li><a href="{{ url('nghiep_vu/quan_ly/quan_he_bt/maso=all') }}">Quan hệ
                                                    gia đình (bản thân)</a></li>
                                            <li><a href="{{ url('nghiep_vu/quan_ly/quan_he_vc/maso=all') }}">Quan hệ
                                                    gia đình (vợ, chồng)</a></li>
                                            {{-- <li><a href="{{url('nghiep_vu/quan_ly/dieu_dong/maso=all')}}">Hồ sơ luân chuyển</a></li> --}}
                                            {{-- <li><a href="{{url('nghiep_vu/quan_ly/chuc_vu/maso=all')}}">Hồ sơ phòng ban, chức vụ</a></li> --}}
                                            {{-- <li><a href="{{url('nghiep_vu/quan_ly/bhyt/maso=all')}}">Theo dõi bảo hiểm y tế</a></li> --}}
                                            <li>
                                                <a href="{{ url('nghiep_vu/quan_ly/llvt/maso=all') }}">Hồ sơ lực lượng
                                                    vũ trang</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <i class="fa fa-folder-open-o"></i> Quản lý quá trình <span
                                                class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu" style="margin-left: 15px;">
                                            <li><a href="{{ url('nghiep_vu/qua_trinh/dao_tao/maso=all') }}">Quá
                                                    trình
                                                    đào tạo</a></li>
                                            <li><a href="{{ url('nghiep_vu/qua_trinh/cong_tac/maso=all') }}">Công
                                                    tác
                                                    trong nước</a></li>
                                            <li><a href="{{ url('nghiep_vu/qua_trinh/cong_tac_nn/maso=all') }}">Công
                                                    tác nước ngoài</a></li>
                                            <li><a href="{{ url('nghiep_vu/qua_trinh/luong/maso=all') }}">Quá trình
                                                    hưởng lương</a></li>
                                            <li><a href="{{ url('nghiep_vu/qua_trinh/phu_cap/maso=all') }}">Quá
                                                    trình
                                                    phụ cấp</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <i class="icon-calendar"></i> Bình bầu, đánh giá <span
                                                class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu" style="margin-left: 15px;">
                                            {{-- <li><a href="{{url('nghiep_vu/danh_gia/binh_bau/maso=all')}}">Bình bầu, phân loại</a></li> --}}
                                            <li><a href="{{ url('nghiep_vu/danh_gia/khen_thuong/maso=all') }}">Khen
                                                    thưởng</a></li>
                                            <li><a href="{{ url('nghiep_vu/danh_gia/ky_luat/maso=all') }}">Kỷ
                                                    luật</a>
                                            </li>
                                            {{-- <li><a href="{{url('nghiep_vu/danh_gia/thanh_tra/maso=all')}}">Thanh tra</a></li> --}}
                                            <li><a href="{{ url('nghiep_vu/danh_gia/nhan_xet/maso=all') }}">Đánh
                                                    giá,
                                                    nhận xét</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="javascript:;">
                                    <i class="fa glyphicon glyphicon-folder-open"></i>
                                    <span class="title">Quản lý</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ url('nghiep_vu/ho_so/danh_sach') }}">
                                            <i class="fa fa-caret-right"></i>Danh sách cán bộ đang công tác</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('nghiep_vu/tam_ngung/danh_sach') }}">
                                            <i class="fa fa-caret-right"></i>Danh sách cán bộ tạm ngừng theo dõi</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('nghiep_vu/truy_linh/danh_sach?thang=ALL' . '&nam=' . date('Y')) }}">
                                            <i class="fa fa-caret-right"></i>Danh sách cán bộ được truy lĩnh lương</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('nghiep_vu/truc/danh_sach?thang=' . date('m') . '&nam=' . date('Y')) }}">
                                            <i class="fa fa-caret-right"></i>Danh sách cán bộ hưởng phụ cấp theo ngày
                                            làm việc</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('nghiep_vu/da_nghi/danh_sach') }}">
                                            <i class="fa fa-caret-right"></i>Danh sách cán bộ đã thôi công tác</a>
                                    </li>
                                    {{-- Bỏ qua do chỉ tiêu biên chế nhập tại dự toán lương 11/02/2023
                                    <li>
                                        <a href="{{ url('nghiep_vu/chi_tieu/danh_sach?namct=' . date('Y')) }}">
                                            <i class="fa fa-caret-right"></i>Chỉ tiêu biên chế</a>
                                    </li> --}}

                                    {{-- Bỏ qua do chưa hoàn thiện 11/02/2023
                                    <li>
                                        <a href="{{ url('nghiep_vu/qua_trinh/phu_cap/danh_sach') }}">
                                            <i class="fa fa-caret-right"></i>Quá trình hưởng lương, phụ cấp</a>
                                    </li> --}}
                                    @if (session('admin')->maphanloai == 'KVXP')
                                        <!-- Tạm thời bỏ để triển khai lạng sơn -->
                                        <!--li><a href="{{ url('nghiep_vu/quan_ly/dia_ban_dbkk/index') }}"><i class="fa fa-caret-right"></i>Danh sách thôn, tổ dân phố</a></li-->
                                    @endif
                                </ul>
                            </li>
                        @endif


                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-sitemap fa-fw"></i>
                                <span class="title">Chức năng</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                {{-- <li> --}}
                                {{-- <a href="{{url('chuc_nang/dang_ky_luong/danh_sach?thang='.date('m').'&nam='.date('Y'))}}"><i class="fa fa-caret-right"></i>Đăng ký lương</a> --}}
                                {{-- </li> --}}

                                <li>
                                    <a
                                        href="{{ url('chuc_nang/bang_luong/chi_tra?thang=' . date('m') . '&nam=' . date('Y')) }}"><i
                                            class="fa fa-caret-right"></i>Chi trả lương</a>
                                </li>

                                <li>
                                    <a href="{{ url('chuc_nang/nang_luong/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Nâng lương ngạch bậc</a>
                                </li>

                                <li>
                                    <a href="{{ url('chuc_nang/tham_nien/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Nâng lương thâm niên nghề</a>
                                </li>

                                <li>
                                    <a href="{{ url('nghiep_vu/dieu_dong/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Luân chuyển cán bộ</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa icon-book-open"></i>
                                <span class="title">Tổng hợp dữ liệu</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ url('chuc_nang/tong_hop_luong/don_vi/index?nam=' . date('Y')) }}"><i
                                            class="fa fa-caret-right"></i>Tổng hợp lương tại đơn vị</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa glyphicon glyphicon-list-alt"></i>
                                <span class="title">Nguồn và dự toán</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('nghiep_vu/quan_ly/du_toan/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Dự toán lương</a></li>
                                <li><a href="{{ url('nguon_kinh_phi/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Nhu cầu kinh phí</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-search"></i>
                                <span class="title">Tra cứu</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/tra_cuu/ho_so') }}"><i class="fa fa-caret-right"></i>Hồ sơ
                                        cán
                                        bộ</a></li>
                                <!--li><a href="{{ url('/tra_cuu/luong') }}"><i class="fa fa-caret-right"></i>Quá trình hưởng lương</a></li>
                            <li><a href="{{ url('/tra_cuu/phu_cap') }}"><i class="fa fa-caret-right"></i>Quá trình phụ cấp</a></li-->
                                <!--li><a href="{{ url('/tra_cuu/chi_luong') }}"><i class="fa fa-caret-right"></i>Bảng lương tại đợn vị cấp dưới</a></li-->
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-file-text"></i>
                                <span class="title">Báo cáo</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <!--li><a href="{{ url('bao_cao/don_vi') }}">Báo cáo nhân sự</a></li  chưa fix -->
                                <!--li><a href="{{ url('bao_cao/mau_chuan') }}">Báo cáo theo thông tư, quyết định</a></li-->
                                <li><a href="{{ url('bao_cao/bang_luong') }}"><i class="fa fa-caret-right"></i>Báo
                                        cáo chi tiết</a></li>
                            </ul>
                        </li>

                        <li class="last">
                            <a href="javascript:;">
                                <i class="fa fa-gear"></i>
                                <span class="title">Hệ thống</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <i class="fa fa-list-alt"></i> Danh mục <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('/danh_muc/phong_ban/index') }}"><i
                                                    class="fa fa-caret-right"></i>Khối(tổ) công tác</a></li>
                                        <li><a href="{{ url('/danh_muc/chuc_vu/index') }}"><i
                                                    class="fa fa-caret-right"></i>Chức vụ</a></li>
                                        <li><a href="{{ url('/danh_muc/ngach_bac/danhsach') }}"><i
                                                    class="fa fa-caret-right"></i>Mã ngạch lương</a></li>
                                        <li><a href="{{ url('danh_muc/cong_tac/don_vi') }}">
                                                <i class="fa fa-caret-right"></i>Phân loại công tác</a></li>
                                        <li><a href="{{ url('danh_muc/thuetncn/index') }}">
                                                <i class="fa fa-caret-right"></i>Thuế thu nhập cá nhân</a></li>
                                        <!--li><a href="{{ url('danh_muc/dan_toc/index') }}"><i class="fa fa-caret-right"></i>Dân tộc</a></li-->
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-user"></i>Người dùng <span class="arrow"></span>
                                    </a>

                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('change-password') }}"><i
                                                    class="fa fa-caret-right"></i>Đổi mật khẩu</a></li>
                                        <!--li><a href="{{ url('phanquyen') }}">Phân quyền</a></li-->
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-grid"></i> Quản trị hệ thống <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('/he_thong/don_vi/don_vi') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin đơn vị</a></li>
                                        <li><a href="{{ url('/he_thong/don_vi/bao_hiem') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin nộp bảo hiểm</a></li>
                                        <li><a href="{{ url('/danh_muc/phu_cap/don_vi') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin phụ cấp</a></li>
                                        <li><a href="{{ url('danh_muc/cong_tac/don_vi') }}">
                                                <i class="fa fa-caret-right"></i>Phân loại công tác</a></li>
                                        {{-- <li><a href="{{url('/danh_muc/thai_san/danh_sach')}}"><i class="fa fa-caret-right"></i>Thông tin phụ cấp thai sản</a></li> --}}
                                        <li><a href="{{ url('/he_thong/dinh_muc/danh_sach') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin định mức nguồn kinh phí</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/he_thong/DonViQuanLy/danh_sach') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin đơn vị quản lý</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- dành cho đơn vị chủ quản -->
                    @elseif(session('admin')->phanloaitaikhoan == 'TH')
                        @if (session('admin')->level != 'T')
                            <li>
                                <a href="javascript:;">
                                    <i class="fa icon-book-open"></i>
                                    <span class="title">Quản lý hồ sơ</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    @if (session('admin')->phamvitonghop == 'HUYEN')
                                        <li>
                                            <a href="{{ url('nghiep_vu/ho_so/danh_sach_th?sunghiep=ALL&madv=ALL') }}"><i
                                                    class="fa fa-caret-right"></i>Danh sách cán bộ</a>
                                        </li>

                                        <li>
                                            <a
                                                href="{{ url('nghiep_vu/ho_so/nang_luong_th?madv=ALL&nangluong=NB') }}"><i
                                                    class="fa fa-caret-right"></i>Danh sách nâng lương</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('nghiep_vu/ho_so/nghi_huu_th?madv=ALL') }}"><i
                                                    class="fa fa-caret-right"></i>Danh sách nghỉ hưu</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <li>
                            <a href="javascript:;">
                                <i class="fa icon-book-open"></i>
                                <span class="title">Chi trả lương</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'KHOI')
                                    <li>
                                        <a href="{{ url('chuc_nang/tong_hop_luong/khoi/index?nam=' . date('Y')) }}"><i
                                                class="fa fa-caret-right"></i>Tổng hợp lương từ đơn vị cấp dưới</a>
                                    </li>

                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/index?thang=' . date('m') . '&nam=' . date('Y') . '&trangthai=ALL' . '&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'HUYEN')
                                    <li>
                                        <a href="{{ url('chuc_nang/tong_hop_luong/huyen/index?nam=' . date('Y')) }}"><i
                                                class="fa fa-caret-right"></i>Tổng hợp lương toàn địa bàn</a>
                                    </li>

                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/huyen?thang=' . date('m') . '&nam=' . date('Y') . '&trangthai=ALL&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp toàn địa bàn</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'T')
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/tong_hop_luong/tinh/index?thang=' . date('m') . '&nam=' . date('Y')) }}"><i
                                                class="fa fa-caret-right"></i>Tổng hợp chi trả lương</a>
                                    </li>

                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/tinh?thang=' . date('m') . '&nam=' . date('Y') . '&trangthai=ALL&madiaban=' . session('admin')->madvbc) }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tại đơn vị</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa icon-book-open"></i>
                                <span class="title">Dự toán lương</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'KHOI')
                                    <li>
                                        <a href="{{ url('chuc_nang/du_toan_luong/khoi/index') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu dự toán</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/du_toan/khoi?namns=' . date('Y') . '&trangthai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'HUYEN')
                                    <li>
                                        <a href="{{ url('chuc_nang/du_toan_luong/huyen/index') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu dự toán</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/du_toan/huyen?namns=' . date('Y') . '&trangthai=ALL&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'T')
                                    <li>
                                        <a href="{{ url('chuc_nang/du_toan_luong/tinh/index') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu dự toán</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/du_toan/tinh?namns=' . date('Y') . '&trangthai=ALL&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa icon-book-open"></i>
                                <span class="title">Nguồn kinh phí</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'KHOI')
                                    <li>
                                        <a href="{{ url('chuc_nang/tong_hop_nguon/khoi/index') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu nguồn kinh phí</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/nguon/khoi?sohieu=TT67_2017&trangthai=ALL&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'H' && session('admin')->phamvitonghop == 'HUYEN')
                                    {{-- <li>
                                        <a href="{{ url('nguon_kinh_phi/huyen/danh_sach') }}"><i
                                                class="fa fa-caret-right"></i>Số liệu nguồn kinh phí</a>
                                    </li> --}}
                                    <li>
                                        <a href="{{ url('chuc_nang/tong_hop_nguon/huyen/index') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu nhu cầu kinh phí</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/nguon/huyen?sohieu=TT67_2017&trangthai=ALL&phanloai=ALL') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif

                                @if (session('admin')->level == 'T')
                                    {{-- <li>
                                    <a href="{{ url('nguon_kinh_phi/huyen/danh_sach') }}"><i
                                            class="fa fa-caret-right"></i>Số liệu nguồn kinh phí</a>
                                </li> --}}
                                    <li>
                                        <a href="{{ url('chuc_nang/tong_hop_nguon/tinh/index?sohieu=TT67_2017') }}"><i
                                                class="fa fa-caret-right"></i>Tổng số liệu nguồn kinh phí</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ url('chuc_nang/xem_du_lieu/nguon/tinh?sohieu=TT67_2017&trangthai=ALL&phanloai=ALL&madiaban=1506415809') }}"><i
                                                class="fa fa-caret-right"></i>Xem số liệu tổng hợp từ đơn vị cấp
                                            dưới</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-file-text"></i>
                                <span class="title">Báo cáo</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @if (session('admin')->level == 'T')
                                    <li><a href="{{ url('tong_hop_bao_cao/danh_sach') }}"><i
                                                class="fa fa-caret-right"></i>Báo cáo tổng hợp</a></li>
                                @endif

                                @if (session('admin')->level == 'H')
                                    <li><a href="{{ url('bao_cao/bang_luong/tong_hop') }}"><i
                                                class="fa fa-caret-right"></i>Báo cáo tổng hợp</a></li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-search"></i>
                                <span class="title">Tra cứu</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/tra_cuu/chi_luong') }}"><i class="fa fa-caret-right"></i>Bảng
                                        lương tại đơn vị</a></li>
                            </ul>
                        </li>

                        <li class="last">
                            <a href="javascript:;">
                                <i class="fa fa-gear"></i>
                                <span class="title">Hệ thống</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-grid"></i> Quản trị hệ thống <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('/he_thong/don_vi/don_vi') }}"><i
                                                    class="fa fa-caret-right"></i>Thông tin đơn vị</a></li>
                                        <li><a
                                                href="{{ url('/he_thong/bao_cao/danh_sach?madvbc=' . session('admin')->madvbc) }}"><i
                                                    class="fa fa-caret-right"></i>Thiết lập báo cáo</a></li>
                                        <li><a href="{{ url('/he_thong/don_vi/stopdv') }}"><i
                                                    class="fa fa-caret-right"></i>Dừng hoạt động đơn vị</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="last">
                            <a href="javascript:;">
                                <i class="fa fa-gear"></i>
                                <span class="title">Hệ thống</span><span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <i class="fa fa-list-alt"></i> Danh mục <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('he_thong/chuc_vu/index') }}">
                                                <i class="fa fa-caret-right"></i>Chức vụ</a></li>
                                        <li><a href="{{ url('he_thong/phu_cap/index') }}">
                                                <i class="fa fa-caret-right"></i>Phụ cấp</a></li>
                                        <li><a href="{{ url('danh_muc/nguon_kinh_phi/index') }}">
                                                <i class="fa fa-caret-right"></i>Nguồn kinh phí</a></li>
                                        <li><a href="{{ url('danh_muc/khoi_pb/index') }}">
                                                <i class="fa fa-caret-right"></i>Lĩnh vực hoạt động</a></li>
                                        <li><a href="{{ url('danh_muc/ngach_bac/index') }}">
                                                <i class="fa fa-caret-right"></i>Mã ngạch lương</a></li>
                                        <li><a href="{{ url('danh_muc/pl_don_vi/index') }}">
                                                <i class="fa fa-caret-right"></i>Phân loại đơn vị</a></li>
                                        <li><a href="{{ url('danh_muc/cong_tac/index') }}">
                                                <i class="fa fa-caret-right"></i>Phân loại công tác</a></li>
                                        <li><a href="{{ url('danh_muc/thong_tu/index') }}">
                                                <i class="fa fa-caret-right"></i>Thông tư, quyết định</a></li>
                                        <li><a href="{{ url('danh_muc/thuetncn/index') }}">
                                                <i class="fa fa-caret-right"></i>Thuế thu nhập cá nhân</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-grid"></i> Quản trị hệ thống <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" style="margin-left: 15px;">
                                        <li><a href="{{ url('/he_thong/quan_tri/he_thong') }}"><i
                                                    class="fa fa-caret-right"></i>Tham số hệ thống</a></li>
                                        <li><a href="{{ url('/danh_muc/tieu_muc/index') }}"><i
                                                    class="fa fa-caret-right"></i>Công thức mục-tiểu mục</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('danh_muc/tai_khoan/list_user?level=H') }}"><i
                                            class="icon-book-open"></i>Quản lý tài khoản</a></li>
                                <li><a href="{{ url('danh_muc/khu_vuc/danh_sach?level=H') }}"><i
                                            class="icon-book-open"></i>Danh sách khu vực, địa bàn quản lý</a></li>
                                <li><a href="{{ url('van_phong/danh_sach/') }}"><i class="icon-book-open"></i>Danh
                                        sách văn phòng hỗ trợ</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>


                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <!-- END SIDEBAR -->

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content" style="padding-top: 0px;">
                <!-- BEGIN PAGE BREADCRUMB -->
                <!--div class="page-bar">
    <ul class="page-breadcrumb breadcrumb">
    <li>
    <i class="fa fa-home"></i>
        <a href="{{ url('') }}">Trang chủ</a>
    <i class="fa fa-angle-right"></i>
    </li>
    <li>
    {{ $pageTitle }}
    </li>
    </ul>

    <div class="page-toolbar">
    <div class="page-toolbar">
    <b><div id="clock"></div></b>
    </div>
    </div>
    </div-->
                <!-- END PAGE BREADCRUMB -->

                @yield('content')
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-tools">
            2016 &copy; LifeSoft <a href="">Tiện ích hơn - Hiệu quả hơn</a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
<script src="{{ url('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ url('assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->

    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="{{ url('js/main.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript">
    </script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/bootstrap-daterangepicker/moment.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript">
    </script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
    <script src="{{ url('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ url('assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/admin/layout4/scripts/layout.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/admin/layout4/scripts/demo.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/admin/pages/scripts/tasks.js') }}" type="text/javascript"></script>

    @yield('custom-script')
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            Demo.init(); // init demo features
        });
    </script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>
