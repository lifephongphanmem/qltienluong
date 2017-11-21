@extends('main')

@section('custom-style')

@stop


@section('custom-script')

@stop

@section('content')


    <h3 class="page-title">
        Thông tin hệ thống<small> phần mềm</small>
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
                        @if(session('admin')->sadmin == 'SA' || session('admin')->sadmin == 'SSA')
                            <a href="{{url($url.'maso='.$model->id.'/edit_global')}}" class="btn btn-default btn-sm">
                                <i class="fa fa-edit"></i> Chỉnh sửa </a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="user" class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td style="width:30%">
                                    <b>Tuổi nghỉ hưu nam</b>
                                </td>
                                <td style="width:15%">
                                    <span class="text-muted">{{$model->tuoinam}}</span>
                                </td>

                                <td style="width:30%">
                                    <b>Thời gian hết tập sự (tháng)</b>
                                </td>
                                <td style="width:15%">
                                    <span class="text-muted">{{$model->tg_hetts}}</span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:30%">
                                    <b>Tuổi nghỉ hưu nữ</b>
                                </td>
                                <td style="width:20%">
                                    <span class="text-muted">{{$model->tuoinu}}</span>
                                </td>
                                <td style="width:30%">
                                    <b>Thời gian xét nâng lương (tháng)</b>
                                </td>
                                <td style="width:20%">
                                    <span class="text-muted">{{$model->tg_xetnl}} </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:30%">
                                    <b>Lương cơ bản</b>
                                </td>
                                <td style="width:20%">
                                    <span class="text-muted">{{number_format($model->luongcb)}}</span>
                                </td>
                                <td style="width:30%">

                                </td>
                                <td style="width:20%">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop