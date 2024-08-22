@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
    <!-- END THEME STYLES -->
@stop


@section('custom-script')
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
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
            {!! Form::open(['url' => $url . 'ma_so=' . $model->username . '/uppermission']) !!}
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-title">
                    <div class="caption" style="color: #000000">
                        Tên tài khoản: {{ $model_taikhoan->name . ' ( Tài khoản truy cập: ' . $model->model_taikhoan . ')' }}
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet-body">
                        <div class="row">
                            <table class="table table-bordered table-hover" id="sample_3">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" width="10%">STT</th>
                                        <th rowspan="2">Mã số</th>
                                        <th rowspan="2">Tên chức năng</th>
                                        <th colspan="3">Phân quyền</th>
                                        <th rowspan="2" width="10%">Thao tác</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Xem</th>
                                        <th>Sửa</th>
                                        <th>Gửi</br>Duyệt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($model as $c1)
                                        <tr>
                                            <td
                                                class="text-uppercase font-weight-bold text-info {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                                {{ romanNumerals($c1->sapxep) }}</td>
                                            <td
                                                class="font-weight-bold {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                                {{ $c1->machucnang }}</td>
                                            <td
                                                class="font-weight-bold {{ $c1->phanquyen == 0 ? 'text-line-through' : '' }}">
                                                {{ $c1->tenchucnang }}</td>
                                            @if ($c1->nhomchucnang)
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                            @else
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-clean btn-icon">
                                                        <i
                                                            class="icon-lg la {{ $c1->danhsach ? 'fa-check text-primary' : 'fa-times-circle text-danger' }} text-primary icon-2x"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-clean btn-icon">
                                                        <i
                                                            class="icon-lg la {{ $c1->thaydoi ? 'fa-check text-primary' : 'fa-times-circle text-danger' }} text-primary icon-2x"></i>
                                                    </button>
                                                </td>

                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-clean btn-icon">
                                                        <i
                                                            class="icon-lg la {{ $c1->hoanthanh ? 'fa-check text-primary' : 'fa-times-circle text-danger' }} text-primary icon-2x"></i>
                                                    </button>
                                                </td>
                                            @endif

                                            <td class="text-center">
                                                <button
                                                    onclick="getChucNang('{{ $c1->machucnang }}','{{ $c1->tenchucnang }}',{{ $c1->phanquyen }},
                                                        {{ $c1->danhsach }}, {{ $c1->thaydoi }}, {{ $c1->hoanthanh }}, {{ $c1->nhomchucnang }}, {{ $c1->xuly }}, {{ $c1->tiepnhan }})"
                                                    class="btn btn-sm btn-clean btn-icon" data-target="#modify-modal"
                                                    title="Thay đổi thông tin" data-toggle="modal">
                                                    <i class="icon-lg la flaticon-edit-1 text-primary"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Cập nhật</button>
                            <a href="{{ url($url . 'list_user?&madv=' . $model->madv) }}" class="btn green"><i
                                    class="fa fa-mail-reply"></i>&nbsp;Quay lại</a>
                        </div>
                    </div>
                </div>

                <!-- END EXAMPLE TABLE PORTLET-->
                {!! Form::hidden('username', $model->username) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <!-- BEGIN DASHBOARD STATS -->

        <!-- END DASHBOARD STATS -->
        <div class="clearfix"></div>


    </div>
@stop
