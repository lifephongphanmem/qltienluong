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
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();

            $("#phanloaipc").change(function() {
                window.location.href = '{{ $furl }}' + '?phanloaipc=' + $('#phanloaipc').val();
            });
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        DANH SÁCH CÁC LOẠI PHỤ CẤP
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-default btn-sm" data-target="#chuyen-modal"
                            data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                            Khôi phục mặc định</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-4">Phân loại phụ cấp </label>
                            <div class="col-md-4">
                                {!! Form::select('phanloaipc', ['ALL' => 'Tất cả phụ cấp', 'COSO' => 'Phụ cấp cơ sở', 'TD' => 'Các phụ cấp đang theo dõi'], $inputs['phanloaipc'], ['id' => 'phanloaipc', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <table id="sample_4" class="table table-hover table-striped table-bordered"
                            style="min-height: 600px">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 3%">STT</th>
                                    <th class="text-center">Mã số</th>
                                    <th>Tên phụ cấp</br>Tiêu đề nhập liệu</br>Tiêu đề báo cáo</th>
                                    <th style="width: 8%" class="text-center">Phân loại</th>
                                    <th class="text-center">Bao gồm các</br>loại hệ số</th>
                                    <th style="width: 25%" class="text-center">Áp dụng cho các</br>phân loại công tác
                                    </th>
                                    <th style="width: 3%" class="text-center">Tổng</br>hợp</th>
                                    <th style="width: 3%" class="text-center">Nộp</br>bảo</br>hiểm</th>
                                    <th style="width: 3%" class="text-center">Trừ</br>nghỉ</br>phép</th>
                                    <th style="width: 3%" class="text-center">Chế</br>độ</br>thai</br>sản</th>
                                    <th style="width: 3%" class="text-center">Chế</br>độ</br>điều</br>động</th>
                                    <th style="width: 3%" class="text-center">Tính</br>thuế</br>thu</br>nhập</th>
                                    <th style="width: 3%" class="text-center">Tính</br>tập</br>sự</th>
                                    <th style="width: 10%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($model as $key => $value)
                                    <tr class="{{ $value->phanloai == 3 ? getTextStatus('CHUADL') : '' }}">
                                        <td class="text-center">{{ $value->stt }}</td>
                                        <td>{{ $value->mapc }}</td>
                                        <td>- {{ $value->tenpc }}</br>- {{ $value->form }}</br>-
                                            {{ $value->report }}
                                        </td>
                                        <td class="text-center">{{ $value->tenphanloai }}
                                            @if ($value->pccoso == 1)
                                                </br><b class="text-primary">(Cơ sở)</b>
                                            @endif
                                        </td>
                                        <td>{{ $value->tencongthuc }}</td>
                                        <td>{{ $value->tenplct }} </td>

                                        <td class="text-center">{!! $value->tonghop == 1 ? '<i class="fa fa-check"></i>' : '' !!} </td>
                                        <td class="text-center">{!! $value->baohiem == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">{!! $value->nghiom == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">{!! $value->thaisan == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">{!! $value->dieudong == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">{!! $value->thuetn == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">{!! $value->tapsu == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td class="text-center">
                                            <a title="Chỉnh sửa" href="{{ $furl . 'edit?maso=' . $value->mapc }}"
                                                class="btn btn-default btn-icon-only">
                                                <i class="fa fa-edit"></i></a>

                                            <a title="Ẩn/Hiện phụ cấp" href="{{ $furl . 'anhien?id=' . $value->id }}"
                                                class="btn btn-default btn-icon-only">
                                                <i class="fa fa-refresh"></i></a>

                                            @if ($value->pccoso != 1)
                                                <button class="btn btn-default btn-icon-only"
                                                    title="Đặt/Hủy làm phụ cấp cơ sở để tính lương cho các phụ cấp khác"
                                                    onclick="setPCCoSo('{{ $value->mapc }}', '{{ $value->madv }}', '{{ $value->tenpc }}')"
                                                    data-target="#pccoso-modal-confirm" data-toggle="modal">
                                                    <i class="fa icon-key"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'default_pc', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý khôi phục thiết lập mặc định?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Các thiết đặt về phụ cấp của bạn sẽ thiết đặt lại. Bạn có chắc chắn muốn khôi
                                khục?</b></label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <!--Model gán phụ cấp cơ sở-->
    <div class="modal fade" id="pccoso-modal-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'pccoso', 'id' => 'frm_pccoso', 'method' => 'POST']) !!}
                {!! Form::hidden('mapc', null) !!}
                {!! Form::hidden('madv', null) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý đặt phụ cấp làm phụ cấp cơ sở</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Bạn đồng ý đặt phụ cấp làm phụ cấp cơ sở để tính lương cho các phụ cấp khác?</b></label>
                    </div>

                    <div class="form-group">
                        <label>Tên phụ cấp</label>
                        {!! Form::text('tenpc', null, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>


    <script>
        $(function() {
            //Multi select box
            //$("#ctpc").select2();
            //<span class="badge badge-default">Chưa hoàn thành</span>
            $("#ctpc").change(function() {
                $("#congthuc").val($("#ctpc").val());
            });
        });

        function setPCCoSo(mapc, madv, tenpc) {
            var form = $('#frm_pccoso');
            form.find("[name='mapc']").val(mapc);
            form.find("[name='madv']").val(madv);
            form.find("[name='tenpc']").val(tenpc);
        }


        function add() {
            $('#ctpc').val('').trigger('change');
            $('#congthuc').val('');
            $('#mapc').val('');
            $('#chitiet-modal').modal('show');
        }

        function getInfo() {
            window.location.href = '{{ $furl }}' + 'maso=' + $('#cbmacb').val();
        }

        function edit(mapc) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mapc: mapc
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#ngaytu').val(data.ngaytu);
                    $('#ngayden').val(data.ngayden);
                    $('#mapc').val(data.mapc);
                    $('#hesopc').val(data.hesopc);
                },
                error: function(message) {
                    alert(message);
                }
            });

            $('#id_ct').val(id);
            $('#chitiet-modal').modal('show');
        }

        function confirm() {
            var valid = true;
            var message = '';

            var mapc = $('#mapc').val();
            var tenpc = $('#tenpc').val();


            if (mapc == '' || tenpc == '') {
                valid = false;
                message += 'Mã số và tên phụ cấp không được bỏ trống \n';
            }
            if (valid) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ $furl }}' + 'store',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        mapc: mapc,
                        tenpc: tenpc,
                        phanloai: $('#phanloai').val(),
                        form: $('#form').val(),
                        report: $('#report').val(),
                        congthuc: $('#congthuc').val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status == 'success') {
                            location.reload();
                        }
                    },
                    error: function(message) {
                        alert(message);
                    }
                });


                $('#chitiet-modal').modal('hide');
            } else {
                alert(message);
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop
