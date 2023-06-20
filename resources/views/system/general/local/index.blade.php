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
                        @if (session('admin')->phanloaitaikhoan != 'TH')
                            <a href="{{ url($url . 'thong_tin?maso=' . $model->madv) }}" class="btn btn-default btn-sm">
                                <i class="fa fa-edit"></i> Chỉnh sửa </a>
                        @endif
                        @if (session('admin')->phanloaitaikhoan == 'TH')
                            <a href="{{ url($url . 'thong_tin_th?maso=' . $model->madv) }}" class="btn btn-default btn-sm">
                                <i class="fa fa-edit"></i> Chỉnh sửa </a>
                        @endif
                        <!--a href="" class="btn btn-default btn-sm">
                                <i class="fa fa-print"></i> Print </a-->
                        @if (session('admin')->sadmin == 'ssa')
                            <a href="{{ url('setting') }}" class="btn btn-default btn-sm">
                                <i class="icon-settings"></i> Setting</a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="user" class="table table-bordered table-striped">
                        <tbody>

                            <tr>
                                <td style="width:15%">
                                    <b>Mã quan hệ ngân sách</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->maqhns }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:15%">
                                    <b>Tên đơn vị</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->tendv }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:15%">
                                    <b>Đơn vị tổng hợp dữ liệu</b>
                                </td>
                                @if (session('admin')->phamvitonghop == 'HUYEN' && session('admin')->phanloaitaikhoan == 'TH')
                                    <td style="width:35%">
                                        <span class="text-muted">Sở Tài Chính</span>
                                    </td>
                                @else
                                    <td style="width:35%">
                                        <span class="text-muted">{{ $model->donviquanly }}</span>
                                    </td>
                                @endif

                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Phân loại đơn vị</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->maphanloai }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Mức độ tự chủ tài chính</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->phanloainguon }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Lĩnh vực hoạt động</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->lvhd }}</span>
                                </td>
                            </tr>
                            @if ($model->maphanloai == 'KVXP')
                                <tr>
                                    <td style="width:15%">
                                        <b>Phân loại xã</b>
                                    </td>
                                    <td style="width:35%">
                                        <span class="text-muted">{{ $model->phanloaixa }}</span>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="width:15%">
                                    <b>Đơn vị chủ quản</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->tendvcq }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Địa chỉ</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->diachi }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Chức danh thủ trưởng</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->cdlanhdao }}</span>
                                </td>
                            </tr>


                            <tr>
                                <td style="width:15%">
                                    <b>Thủ trưởng đơn vị</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->lanhdao }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:15%">
                                    <b>Người lập biểu</b>
                                </td>
                                <td style="width:35%">
                                    <span class="text-muted">{{ $model->nguoilapbieu }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
