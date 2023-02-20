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
    <link rel="stylesheet" type="text/css" href="{{ url('css/customstyle.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption text-uppercase">Thuyết minh chi tiết bảng lương</div>
                    <div class="actions">
                        @if ($inputs['thaotac'])
                            {{-- <button type="button" class="btn btn-default btn-xs" data-target="#modal-TaoThuyetMinh"
                                data-toggle="modal"><i class="fa fa-refresh"></i>&nbsp;Tính lại
                            </button> --}}
                            <button type="button" class="btn btn-default btn-xs" data-target="#modal-LuuThuyetMinh"
                                data-toggle="modal" onclick="getThuyetMinh(-1)"><i class="fa fa-plus"></i>&nbsp;Thêm mới
                            </button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row" style="padding-bottom: 6px;">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">Tháng </label>
                                {!! Form::select('thangct', getThang(), $model_tm['thang'], ['id' => 'thangct', 'class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-3">
                                <label class="control-label">Năm </label>
                                {!! Form::select('namct', getNam(), $model_tm['nam'], ['id' => 'namct', 'class' => 'form-control']) !!}
                            </div>
                           
                            <div class="col-md-6">
                                <label class="control-label">Chênh lệch</label>
                                {!! Form::text('chenhlech', $inputs['chenhlech'], [
                                    'class' => 'form-control text-right',
                                    'data-mask' => 'fdecimal',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <table id="sample_4" class="table table-hover table-striped table-bordered"
                                    style="min-height: 230px">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">STT</th>
                                            <th style="width:10%" class="text-center">Tăng / Giảm</th>
                                            <th class="text-center">Nội dung</th>
                                            <th style="width:10%" class="text-center">Số tiền</th>
                                            <th class="text-center">Ghi chú</th>
                                            <th style="width:15%" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <?php $i = 1; ?>
                                    <tbody>
                                        @foreach ($model as $key => $value)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td>{{ $value->tanggiam == 'TANG' ? 'Tăng' : 'Giảm' }}</td>
                                                <td>{{ $value->noidung }}</td>
                                                <td class="text-right">{{ dinhdangso($value->sotien) }}</td>
                                                <td>{{ $value->ghichu }}</td>
                                                <td class="text-center">
                                                    @if ($inputs['thaotac'])
                                                        <button type="button"
                                                            onclick="getThuyetMinh('{{ $value->id }}')"
                                                            class="btn btn-default btn-xs mbs"
                                                            data-target="#modal-LuuThuyetMinh" data-toggle="modal">
                                                            <i class="fa fa-edit"></i>&nbsp;Sửa
                                                        </button>

                                                        <button type="button"
                                                            onclick="cfDel('{{ '/chuc_nang/bang_luong/XoaThuyetMinhChiTiet' }}', '{{ $value->id }}')"
                                                            class="btn btn-danger btn-xs mbs"
                                                            data-target="#delete-modal-confirm" data-toggle="modal">
                                                            <i class="fa fa-trash-o"></i>&nbsp;Xóa
                                                        </button>
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
                <div class="row">
                    <div class="col-md-offset-5 col-md-8">
                        <a href="{{ url('/chuc_nang/bang_luong/chi_tra?thang=' . $model_tm->thang . '&nam=' . $model_tm->nam) }}"
                            class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>

                    </div>
                </div>
            </div>
        </div>
    </div>    

    {{-- Sửa Thuyết minh chi tiết --}}
    {!! Form::open([
        'url' => '/chuc_nang/bang_luong/LuuThuyetMinh',
        'method' => 'post',
        'files' => true,
        'id' => 'frmLuuThuyetMinh',
    ]) !!}
    <div id="modal-LuuThuyetMinh" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">Thông tin chi tiết
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            </div>
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-7">
                        <label class="control-label">Phân loại</label>
                        {!! Form::select('phanloai', getPhanLoaiThuyetMinh(), null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-5">
                        <label class="control-label">Tăng / Giảm</label>
                        {!! Form::select('tanggiam', ['TANG' => 'Tăng', 'GIAM' => 'Giảm'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Tên cán bộ / Tên phụ cấp</label>
                        {!! Form::text('noidung', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Số tiền</label>
                        <div class="input-group bootstrap-touchspin">
                            {!! Form::text('sotien', null, ['class' => 'form-control text-right', 'data-mask' => 'fdecimal']) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Ghi chú</label>
                        {!! Form::textarea('ghichu', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>
                <input type="hidden" name="mathuyetminh" value="{{ $inputs['mathuyetminh'] }}" />
                <input type="hidden" name="id" />
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                    ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function getThuyetMinh(id) {
            var form = $('#frmLuuThuyetMinh');
            form.find("[name='id']").val(id);
            if (id == -1) {
                form.find("[name='noidung']").val('');
                form.find("[name='sotien']").val(0);
                form.find("[name='ghichu']").val('');
            } else {
                $.ajax({
                    url: '/chuc_nang/bang_luong/LayThuyetMinh',
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        form.find("[name='noidung']").val(data.noidung);
                        form.find("[name='sotien']").val(data.sotien);
                        form.find("[name='ghichu']").val(data.ghichu);
                        form.find("[name='tanggiam']").val(data.tanggiam).trigger('change');
                        form.find("[name='phanloai']").val(data.phanloai).trigger('change');
                    },
                    error: function(message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });
            }
        }
    </script>
    @include('includes.modal.delete-post')
@stop
