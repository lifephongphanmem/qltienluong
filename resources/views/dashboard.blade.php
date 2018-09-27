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
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">danh sách cán bộ sắp đến kỳ nâng lương ngạch bậc</span>
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
                            <th class="text-center">Từ ngày</th>
                            <th class="text-center">Đến ngày</th>
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
                                    <td>{{getDayVn($value->ngaytu)}}</td>
                                    <td>{{getDayVn($value->ngayden)}}</td>
                                    <td>{{getDayVn($value->ngaynangluong)}}</td>
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
        <div class="col-sm-12">
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
                    <table id="sample_4" class="table table-hover table-striped table-bordered">
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