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
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS -->
    <!--div class="row margin-top-10"-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Thông tin hỗ trợ</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">

                    <p>Công ty LifeSoft chân thành cảm ơn quý khách hàng đã tin tưởng sử dụng phần mềm của công ty.
                        Thay mặt toàn bộ cán bộ nhân viên trong công ty gửi đến khách hàng lời chúc sức khỏe- thành công</p>
                    <p>Nhằm chăm sóc, hỗ trợ khách hàng nhanh chóng và tiện dụng nhất công ty xin cung cấp thông tin các cán bộ hỗ trợ khách hàng trong quá trình sử dụng.
                        Mọi vấn đề khúc mắc khách hàng có thể gọi điện thoại trực tiếp cho cán bộ để được hỗ trợ nhanh nhất có thể!</p>
                    <p>Số điện thoại công ty: <b>0243.6343.951</b></p>
                    <p>Phụ trách khối kỹ thuật:<b> Phó giám đốc:  Trần Ngọc Hiếu </b>- tel: <b>0968.206.844</b></p>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Phòng TKBT I - quản lý địa bàn các tỉnh phía Nam</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>

                <div class="portlet-body">
                    <table id="sample_7" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="2%">STT</th>
                            <th width="40%">Họ và tên</th>
                            <th>Chức vụ</th>
                            <th width="25%">Điện thoại</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1</td>
                            <td style="font-weight: bold;">Nguyễn Xuân Trường</td>
                            <td>Trưởng phòng</td>
                            <td>0917.737.456</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">2</td>
                            <td style="font-weight: bold">Hoàng Ngọc Long</td>
                            <td>Phó phòng</td>
                            <td>0985.365.683</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">3</td>
                            <td style="font-weight: bold">Nguyễn Văn Hiển</td>
                            <td>Nhân viên</td>
                            <td>0975.500.274</td>
                        </tr>
                        <tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Phòng TKBT II - quản lý địa bàn các tỉnh phía Bắc</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>

                <div class="portlet-body">
                    <table id="sample_8" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="2%">STT</th>
                            <th width="40%">Họ và tên</th>
                            <th>Chức vụ</th>
                            <th width="25%">Điện thoại</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1</td>
                            <td style="font-weight: bold">Hoàng Văn Sáng</td>
                            <td>Trưởng phòng</td>
                            <td>0974.090.556</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">2</td>
                            <td style="font-weight: bold">Nguyễn Văn Dũng</td>
                            <td>Phó phòng</td>
                            <td>0986.012.094</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">3</td>
                            <td style="font-weight: bold">Nguyễn Văn Đạt</td>
                            <td>Nhân viên</td>
                            <td>0966.305.359</td>
                        </tr>
                        <tr>
                            <td style="text-align: center">4</td>
                            <td style="font-weight: bold">Ngô Thế Dương</td>
                            <td>Nhân viên</td>
                            <td>0916.678.911</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <small>SỰ NGHIỆP CÁN BỘ</small>
                    </div>
                    <div class="icon">
                        <i class="icon-pie-chart"></i>
                    </div>
                </div>
                <div class="">
                    <li class="list-group-item">Công chức<span class="badge badge-info pull-right">
                            {{number_format($a_ketqua['congchuc'])}}</span></li>
                    <li class="list-group-item">Viên chức<span class="badge badge-info pull-right">
                            {{number_format($a_ketqua['vienchuc'])}}</span></li>
                    <li class="list-group-item">Khác<span class="badge badge-info pull-right">
                            {{number_format($a_ketqua['khac'])}}</span></li>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <small>PHÂN LOẠI CÁN BỘ</small>
                    </div>
                    <div class="icon">
                        <i class="icon-pie-chart"></i>
                    </div>
                </div>
                <div class="">
                    <li class="list-group-item">Chính thức<span
                                class="badge badge-info pull-right">{{number_format($a_ketqua['chinhthuc'])}}</span></li>
                    <li class="list-group-item">Tập sự<span
                                class="badge badge-info pull-right">{{number_format($a_ketqua['tapsu'])}}</span></li>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <small>GIỚI TÍNH</small>
                    </div>
                    <div class="icon">
                        <i class="icon-pie-chart"></i>
                    </div>
                </div>
                <div class="">
                    <li class="list-group-item">Nam<span
                                class="badge badge-info pull-right">{{number_format($a_ketqua['gt_nam'])}}</span></li>
                    <li class="list-group-item">Nữ<span
                                class="badge badge-info pull-right">{{number_format($a_ketqua['gt_nu'])}}</span></li>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Cán bộ nâng lương thâm niên nghề</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <?php $i=1;?>
                <div class="portlet-body">
                    <table id="sample_3" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Họ và tên</th>
                            <th class="text-center">Mã ngạch</th>
                            <th class="text-center">Ngày nâng lương</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($m_nghe))
                            @foreach($m_nghe as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{$value->msngbac}}</td>
                                    <td>{{getDayVn($value->tnndenngay)}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>

        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">cán bộ nâng lương ngạch bậc</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <?php $i=1;?>
                <div class="portlet-body">
                    <table id="sample_4" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Họ và tên</th>
                            <th class="text-center">Mã ngạch</th>
                            <th class="text-center">Ngày nâng lương</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($m_nangluong))
                            @foreach($m_nangluong as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{$value->msngbac}}</td>
                                    <td>{{getDayVn($value->ngayden)}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">danh sách cán bộ chờ nhận</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <?php $i=1;?>
                <div class="portlet-body">
                    <table id="sample_6" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10%" class="text-center">STT</th>
                            <th class="text-center">Họ và tên</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($m_luanchuyen))
                            @foreach($m_luanchuyen as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>

        <div class="col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">danh sách cán bộ sắp đến tuổi nghỉ hưu</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <?php $i=1;?>
                <div class="portlet-body">
                    <table id="sample_5" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Họ và tên</th>
                            <th class="text-center">Ngày nghỉ hưu</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($m_nghihuu))
                            @foreach($m_nghihuu as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{getDayVn($value->ngaynghi)}}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
@stop