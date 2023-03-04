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
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH ĐƠN VỊ QUẢN LÝ</div>
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-xs" onclick="edit(-1,{{ date('Y') }},null)"
                            data-target="#modal-ThemMoi" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thêm
                            mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%">STT</th>
                                <th style="width: 10%">Năm quản lý</th>
                                <th style="width: 15%">Mã đơn vị</th>
                                <th>Tên đơn vị</th>
                                <th style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $value->nam }}</td>
                                        <td class="text-center">{{ $value->macqcq }}</td>
                                        <td>{{ $a_donvi[$value->macqcq] ?? $value->macqcq }}</td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#modal-ThemMoi"
                                                onclick="edit('{{ $value->id }}','{{ $value->nam }}','{{ $value->macqcq }}')"
                                                class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa
                                            </button>

                                            <button type="button"
                                                onclick="cfDel('{{ $inputs['url'] . '/Xoa/' . $value->id }}')"
                                                class="btn btn-default btn-xs" data-target="#delete-modal-confirm"
                                                data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chức vụ -->
    <div id="modal-ThemMoi" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['id' => 'frm_ThemMoi', 'url' => $inputs['url'] . '/ThayDoi']) !!}
        <input type="hidden" name="id" />
        <input type="hidden" name="madv" value="{{ $inputs['madv'] }}" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin đơn vị quản lý</h4>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-control-label">Năm quản lý</label>
                                {!! Form::text('nam', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Đơn vị quản lý</label>
                                {!! Form::select('macqcq', $a_donvi, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script>
        function edit(id, nam, macqcq) {
            var form = $('#frm_ThemMoi');
            form.find("[name='id']").val(id);
            form.find("[name='nam']").val(nam);
            if (macqcq != null)
                form.find("[name='macqcq']").val(macqcq);
        }
    </script>

    @include('includes.modal.delete')
@stop
