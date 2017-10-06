@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <!-- END THEME STYLES -->
@stop


@section('custom-script')
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>

@stop

@section('content')

    <h3 class="page-title">
        Quản lý <small>&nbsp;tài khoản</small>
    </h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['url' =>$url.'ma_so='.$model->username.'/uppermission'])!!}
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-title">
                    <div class="caption" style="color: #000000">
                        Tên tài khoản: {{$model->name .' ( Tài khoản truy cập: '. $model->username. ')' }}
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h4 style="text-align: center;color: #0000ff">Quản lý dữ liệu</h4>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="action">
                                    <tr>
                                        <th class="table-checkbox" width="5%">
                                            <!--input type="checkbox" class="group-checkable" data-set="#sample_3 .checkboxes"/-->
                                        </th>
                                        <th>Chức năng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->data->create) && $permission->data->create == 1) ? 'checked' : '' }} value="1" name="roles[data][create]"/></td>
                                        <td>Thêm mới</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->data->edit) && $permission->data->edit == 1) ? 'checked' : '' }} value="1" name="roles[data][edit]"/></td>
                                        <td>Chỉnh sửa</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->data->delete) && $permission->data->delete == 1) ? 'checked' : '' }} value="1" name="roles[data][delete]"/></td>
                                        <td>Xóa</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->data->units) && $permission->data->units == 1) ? 'checked' : '' }} value="1" name="roles[data][units]"/></td>
                                        <td>Xem dữ liệu đơn vị cấp dưới</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->data->reports) && $permission->data->reports == 1) ? 'checked' : '' }} value="1" name="roles[data][reports]"/></td>
                                        <td>Gửi báo cáo</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-3">
                                <h4 style="text-align: center;color: #0000ff">Quản lý hệ thống</h4>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="action">
                                    <tr>
                                        <th class="table-checkbox" width="5%">
                                            <!--input type="checkbox" class="group-checkable" data-set="#sample_3 .checkboxes"/-->
                                        </th>
                                        <th>Chức năng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->system->information) && $permission->system->information == 1) ? 'checked' : '' }} value="1" name="roles[system][information]"/></td>
                                        <td>Thay đổi thông tin đơn vị</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->system->create) && $permission->system->create == 1) ? 'checked' : '' }} value="1" name="roles[system][create]"/></td>
                                        <td>Thêm mới danh mục</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->system->edit) && $permission->system->edit == 1) ? 'checked' : '' }} value="1" name="roles[system][edit]"/></td>
                                        <td>Chỉnh sửa danh mục</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" {{ (isset($permission->system->delete) && $permission->system->delete == 1) ? 'checked' : '' }} value="1" name="roles[system][delete]"/></td>
                                        <td>Xóa danh mục</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Cập nhật</button>
                        <a href="{{url($url.'list_user?&madv='.$model->madv)}}" class="btn green"><i class="fa fa-mail-reply"></i>&nbsp;Quay lại</a>
                        </div>
                    </div>
                </div>

                <!-- END EXAMPLE TABLE PORTLET-->
                {!! Form::hidden('username', $model->username)!!}
                {!! Form::close() !!}
            </div>
        </div>

        <!-- BEGIN DASHBOARD STATS -->

        <!-- END DASHBOARD STATS -->
        <div class="clearfix"></div>


</div>
@stop