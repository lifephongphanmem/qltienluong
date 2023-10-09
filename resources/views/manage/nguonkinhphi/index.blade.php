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
    @include('includes.script.scripts')
    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
            $('#sohieu').change(function() {
                window.location.href = "{{ $inputs['furl'] }}" + 'danh_sach?sohieu=' + $("#sohieu").val();
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
                        <i class="fa fa-list-alt"></i> DANH SÁCH NGUỒN KINH PHÍ CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-default btn-xs" onclick="add()"><i
                                class="fa fa-plus"></i>&nbsp;Thêm mới
                        </button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-6">
                                <label class="form-control-label text-bold">Thông tư, quyết định</label>
                                {!! Form::select('sohieu', $a_thongtuqd, $inputs['sohieu'], [
                                    'id' => 'sohieu',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 300px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân</br>sách</th>
                                <th class="text-center">Lĩnh vực hoạt động</th>
                                <th class="text-center">Nhu cầu</br>kinh phí</th>
                                <th class="text-center">Đơn vị nhận dữ liệu</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($model as $key => $value)
                                <tr class="{{ getTextStatus($value->trangthai) }}">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $value->namns }}</td>
                                    <td>{{ $value->linhvuc }}</td>
                                    <td class="text-right">{{ number_format($value->nhucau) }}</td>
                                    <td>{{ getTenDV($value->macqcq) }}</td>
                                    <td>{{ $a_trangthai[$value->trangthai] }}</td>
                                    <td>
                                        @if ($value->trangthai != 'DAGUI')
                                            <a href="{{ url($inputs['furl'] . 'chi_tiet?maso=' . $value->masodv) }}"
                                                class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>

                                            <button type="button" class="btn btn-default btn-xs mbs"
                                                onclick="confirmChuyen('{{ $value['masodv'] }}')"
                                                data-target="#chuyen-modal" data-toggle="modal"><i
                                                    class="fa fa-share-square-o"></i>&nbsp;
                                                Gửi dữ liệu</button>

                                            <button type="button"
                                                onclick="cfDel('{{ $inputs['furl'] . 'del/' . $value->id }}')"
                                                class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm"
                                                data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                        @endif

                                        @if ($value->trangthai == 'TRALAI')
                                            <button type="button" class="btn btn-default btn-xs"
                                                onclick="getLyDo('{{ $value->masodv }}')" data-target="#tralai-modal"
                                                data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                Lý do trả lại</button>
                                        @endif

                                        <button type="button"
                                            onclick="indutoan('{{ $value->namns }}','{{ $value->masodv }}')"
                                            class="btn btn-default btn-xs mbs" data-target="#indt-modal"
                                            data-toggle="modal">
                                            <i class="fa fa-print"></i>&nbsp; In số liệu
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!--Modal thêm mới -->
    {!! Form::open([
        'url' => $inputs['furl'] . 'create',
        'method' => 'POST',
        'id' => 'create_dutoan',
        'class' => 'horizontal-form',
    ]) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin nguồn kinh phí</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Lĩnh vực hoạt động</label>
                            {!! Form::select(
                                'linhvuchoatdong',
                                getLinhVucHoatDong(false),
                                session('admin')->maphanloai == 'KVXP' ? 'QLNN' : session('admin')->linhvuchoatdong,
                                ['id' => 'linhvuchoatdong', 'class' => 'form-control'],
                            ) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Căn cứ thông tư, quyết định</label>
                            {!! Form::select('sohieu', $a_thongtuqd, $model_tt_df->sohieu, [
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Mức lương định mức</label>
                            {!! Form::text('muccu', $model_tt_df->muccu, [
                                'id' => 'muccu',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'readonly',
                            ]) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Mức lương áp dụng</label>
                            {!! Form::text('mucapdung', $model_tt_df->mucapdung, [
                                'id' => 'mucapdung',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'readonly',
                            ]) !!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Mức chênh lệch</label>
                            {!! Form::text('chenhlech', $model_tt_df->chenhlech, [
                                'id' => 'chenhlech',
                                'class' => 'form-control',
                                'data-mask' => 'fdecimal',
                                'readonly',
                            ]) !!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Nội dung</label>
                            {!! Form::textarea('noidung', null, ['id' => 'noidung', 'class' => 'form-control', 'rows' => '3']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" id="nghihuu" name="nghihuu" />
                            <label for="nghihuu">Tính dự toán cho cán bộ nghỉ hưu</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" checked id="thaisan" name="thaisan" />
                            <label for="thaisan">Tính thời gian nghỉ thai sản của cán bộ</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" checked id="kyluat" name="kyluat" />
                            <label for="kyluat">Tính thời gian kỷ luật của cán bộ</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" checked id="nangluong" name="nangluong" />
                            <label for="nangluong">Tính nâng lương cán bộ</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" style="padding-top: 15px">
                            <input type="checkbox" checked id="tachkiemnhiem" name="tachkiemnhiem" />
                            <label for="tachkiemnhiem">Tách cán bộ HĐND và Cấp uỷ ra làm cán bộ mới</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary"
                        onclick="disable_btn(this)">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $inputs['furl'] . 'senddata', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi
                                gửi.</b></label>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Đơn vị tiếp nhận dữ liệu<span
                                            class="require">*</span></label>
                                    {!! Form::select('macqcq', $a_cqcq, session('admin')->macqcq, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
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

    <!--Model Trả lại -->
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin lý do trả lại dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
                    </div>

                    <div class="modal-footer">
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
        function taonhucau(mabl, thang) {
            var form = $('#create_dutoan_mau');
            form.find("[id='mabl']").val(mabl);
            form.find("[id='thang']").val(thang);
            form.submit();
        }

        function disable_btn(obj) {
            obj.visibled = false;
        }

        function indutoan(namdt, masodv) {
            $('#nam_dt').val(namdt);
            $('#masodv_dt').val(masodv);
        }

        function intonghopdt(url) {
            var masodv = $('#masodv_dt').val();
            window.open(url + masodv, '_blank');
        }

        function innangluong() {
            var masodv = $('#masodv_dt').val();
            window.open('{{ $inputs['furl'] }}' + 'nangluong?maso=' + masodv, '_blank');
        }

        function getLyDo(masodv) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $inputs['furl'] }}' + 'getlydo',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    masodv: masodv
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#lydo').val(data.lydo);
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });
        }

        $(function() {
            $("#sohieu").change(function() {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var form = $('#create_dutoan');
                $.ajax({
                    url: '/nguon_kinh_phi/get_thongtu',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        sohieu: $("#sohieu").val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        form.find("[id='muccu']").val(data.muccu);
                        form.find("[id='mucapdung']").val(data.mucapdung);
                        form.find("[id='chenhlech']").val(data.chenhlech);
                    }
                });
            });

            $('#sohieu_mau').change(function() {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var form = $('#create_dutoan_mau');
                $.ajax({
                    url: '/nguon_kinh_phi/get_thongtu',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        sohieu: $("#sohieu_mau").val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        form.find("[id='muccu_mau']").val(data.muccu);
                        form.find("[id='mucapdung_mau']").val(data.mucapdung);
                        form.find("[id='chenhlech_mau']").val(data.chenhlech);
                    }
                });
            });

            $('#create_dutoan :submit').click(function() {
                var str = '';
                var ok = true;

                if (!$('#sohieu').val()) {
                    str += '  - Số hiệu thông tư, quyết định \n';
                    $('#sohieu').parent().addClass('has-error');
                    ok = false;
                }

                //Kết quả
                if (ok == false) {
                    alert('Các trường: \n' + str + 'Không được để trống');
                    $("#create_dutoan").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("#create_dutoan").unbind('submit').submit();
                }
            });
        });

        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        function add() {
            $('#create-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')
    @include('manage.nguonkinhphi.modal_printf')
@stop
