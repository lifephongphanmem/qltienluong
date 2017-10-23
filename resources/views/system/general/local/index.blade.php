@extends('main')

@section('custom-style')

@stop


@section('custom-script')

@stop

@section('content')


    <h3 class="page-title">
        Quản lý thông tin <small>đơn vị</small>
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-title">
                    <div class="caption">
                    </div>
                    <div class="actions">
                        <a href="{{url($url.'maso='.$model->madv.'/edit_local')}}" class="btn btn-default btn-sm">
                            <i class="fa fa-edit"></i> Chỉnh sửa </a>
                        <!--a href="" class="btn btn-default btn-sm">
                            <i class="fa fa-print"></i> Print </a-->
                        @if(session('admin')->sadmin == 'ssa')
                            <a href="{{url('setting')}}" class="btn btn-default btn-sm">
                                <i class="icon-settings"></i> Setting</a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="user" class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <td style="width:15%">
                                <b>Mã cơ quan chủ quản</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->macqcq}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Mã quan hệ ngân sách</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->madv}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Tên đơn vị</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->tendv}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Cấp dự toán</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->capdutoan}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Địa chỉ</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->diachi}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Chức danh thủ trưởng</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->cdlanhdao}}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:15%">
                                <b>Thủ trưởng đơn vị</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->lanhdao}}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:15%">
                                <b>Người lập biểu</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->nguoilapbieu}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">
                                <b>Khối phòng ban</b>
                            </td>
                            <td style="width:35%">
                                <span class="text-muted">{{$model->khoiphongban}}
                                </span>
                            </td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop