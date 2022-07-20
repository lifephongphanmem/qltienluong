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
                    <div class="caption">
                        THÔNG TIN THIẾT LẬP BÁO CÁO 123
                    </div>
                    <div class="actions">
                        {{-- @if (can('dmphanloaidonvi_baocao', 'create')) --}}
                        <a href="{{ url('/he_thong/bao_cao/inbaocao?madvbc=' . session('admin')->madvbc) }}"
                            class="btn btn-default btn-xs" target="_blank"><i class="fa fa-print"></i>&nbsp;In báo cáo</a>

                        <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="add()"><i
                                class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Mã số</th>
                                <th class="text-center">Tên phân loại</th>
                                <th class="text-center">Cấp độ</th>
                                <th class="text-center">Mã gốc</th>
                                <th class="text-center">In chi tiết</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model->where('capdo_nhom', '1')->sortby('sapxep') as $value1)
                                    <tr>
                                        <td>{{ $value1->sapxep }}</td>
                                        <td>{{ $value1->maphanloai_nhom }}</td>
                                        <td>{{ $value1->tenphanloai_nhom }}</td>
                                        <td>{{ $value1->capdo_nhom }}</td>
                                        <td>{{ $value1->maphanloai_goc }}</td>
                                        <td>{{ $value1->chitiet }}</td>
                                        <td>
                                            <button type="button"
                                                onclick="editPB('{{ $value1->maphanloai_nhom }}','{{ $value1->tenphanloai_nhom }}',
                                                '{{ $value1->capdo_nhom }}','{{ $value1->maphanloai_goc }}','{{ $value1->chitiet }}','{{ $value1->sapxep }}')"
                                                class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            <button type="button"
                                                onclick="cfDel('/he_thong/bao_cao/del/{{ $value1->id }}')"
                                                class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm"
                                                data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                        </td>
                                    </tr>
                                    @foreach ($model->where('maphanloai_goc', $value1->maphanloai_nhom)->sortby('sapxep') as $value2)
                                        <tr>
                                            <td>{{ $value1->sapxep }}.{{ $value2->sapxep }}</td>
                                            <td>{{ $value2->maphanloai_nhom }}</td>
                                            <td>{{ $value2->tenphanloai_nhom }}</td>
                                            <td>{{ $value2->capdo_nhom }}</td>
                                            <td>{{ $value2->maphanloai_goc }}</td>
                                            <td>{{ $value2->chitiet }}</td>
                                            <td>
                                                <button type="button"
                                                    onclick="editPB('{{ $value2->maphanloai_nhom }}','{{ $value2->tenphanloai_nhom }}',
                                                '{{ $value2->capdo_nhom }}','{{ $value2->maphanloai_goc }}','{{ $value2->chitiet }}','{{ $value2->sapxep }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                                <button type="button"
                                                    onclick="cfDel('/he_thong/bao_cao/del/{{ $value2->id }}')"
                                                    class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm"
                                                    data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            </td>
                                        </tr>
                                        @foreach ($model->where('maphanloai_goc', $value2->maphanloai_nhom)->sortby('sapxep') as $value3)
                                            <tr>
                                                <td>{{ $value1->sapxep }}.{{ $value2->sapxep }}.{{ $value3->sapxep }}
                                                </td>
                                                <td>{{ $value3->maphanloai_nhom }}</td>
                                                <td>{{ $value3->tenphanloai_nhom }}</td>
                                                <td>{{ $value3->capdo_nhom }}</td>
                                                <td>{{ $value3->maphanloai_goc }}</td>
                                                <td>{{ $value3->chitiet }}</td>
                                                <td>
                                                    <button type="button"
                                                        onclick="editPB('{{ $value3->maphanloai_nhom }}','{{ $value3->tenphanloai_nhom }}',
                                                '{{ $value3->capdo_nhom }}','{{ $value3->maphanloai_goc }}','{{ $value3->chitiet }}','{{ $value3->sapxep }}')"
                                                        class="btn btn-default btn-xs mbs">
                                                        <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                                    <button type="button"
                                                        onclick="cfDel('/he_thong/bao_cao/del/{{ $value3->id }}')"
                                                        class="btn btn-danger btn-xs mbs"
                                                        data-target="#delete-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                                </td>
                                            </tr>
                                            @foreach ($model->where('maphanloai_goc', $value3->maphanloai_nhom)->sortby('sapxep') as $value4)
                                                <tr>
                                                    <td>{{ $value1->sapxep }}.{{ $value2->sapxep }}.{{ $value3->sapxep }}.{{ $value4->sapxep }}
                                                    </td>
                                                    <td>{{ $value4->maphanloai_nhom }}</td>
                                                    <td>{{ $value4->tenphanloai_nhom }}</td>
                                                    <td>{{ $value4->capdo_nhom }}</td>
                                                    <td>{{ $value4->maphanloai_goc }}</td>
                                                    <td>{{ $value4->chitiet }}</td>
                                                    <td>
                                                        <button type="button"
                                                            onclick="editPB('{{ $value4->maphanloai_nhom }}','{{ $value4->tenphanloai_nhom }}',
                                                                '{{ $value4->capdo_nhom }}','{{ $value4->maphanloai_goc }}','{{ $value4->chitiet }}','{{ $value4->sapxep }}')"
                                                            class="btn btn-default btn-xs mbs">
                                                            <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                                            <button type="button"
                                                                onclick="cfDel('/he_thong/bao_cao/del/{{ $value4->id }}')"
                                                                class="btn btn-danger btn-xs mbs"
                                                                data-target="#delete-modal-confirm" data-toggle="modal">
                                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin phòng ban -->
    {!! Form::open(['url' => '/he_thong/bao_cao/store', 'id' => 'frm_them', 'method' => 'POST']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi tiết</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="form-control-label">Mã số phân loại</label>
                                {!! Form::text('maphanloai_nhom', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label">Thêm</label>
                                <button type="button" class="btn btn-default" data-target="#maphanloai-modal"
                                    data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <label class="form-control-label">Tên phân loại</label>
                    {!! Form::text('tenphanloai_nhom', null, ['class' => 'form-control']) !!}

                    <label class="form-control-label">Cấp độ</label>
                    {!! Form::select('capdo_nhom', ['1' => '1', '2' => '2', '3' => '3', '4' => '4'], null, [
                        'class' => 'form-control',
                    ]) !!}

                    <label class="form-control-label">Sắp xếp</label>
                    {!! Form::text('sapxep', 1, ['class' => 'form-control']) !!}

                    <label class="form-control-label">Mã số phân loại gốc</label>
                    {!! Form::select('maphanloai_goc', $a_nhomgoc, null, ['class' => 'form-control']) !!}

                    <label class="form-control-label">In chi tiết đơn vị</label>
                    {!! Form::select('chitiet', ['0' => 'Gộp các đơn vị thành nhóm', '1' => 'In chi tiết từng đơn vị'], null, [
                        'class' => 'form-control',
                    ]) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div id="maphanloai-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi tiết</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Mã số phân loại gốc</label>
                                {!! Form::select('maphanloai', $a_phanloai, null, ['id' => 'maphanloai_chon', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="button" onclick="setMaPhanLoai()" class="btn btn-primary">Đồng
                        ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function add() {
            $('#ctpc').val('').trigger('change');
            $('#congthuc').val('');
            $('#mapc').val('');
            $('#create-modal').modal('show');
        }

        function getMaPhanLoai() {
            $('#maphanloai-modal').modal('show');
        }

        function setMaPhanLoai() {
            $('#frm_them').find("[name^='maphanloai_nhom']").val($('#maphanloai_chon').val());
            $('#frm_them').find("[name^='tenphanloai_nhom']").val($('#maphanloai_chon option:selected').text());
            $('#maphanloai-modal').modal('hide');
        }

        function editPB(maphanloai_nhom, tenphanloai_nhom, capdo_nhom, maphanloai_goc, chitiet, sapxep) {
            $('#frm_them').find("[name^='maphanloai_nhom']").val(maphanloai_nhom);
            $('#frm_them').find("[name^='tenphanloai_nhom']").val(tenphanloai_nhom);
            $('#frm_them').find("[name^='capdo_nhom']").val(capdo_nhom).trigger('change');
            $('#frm_them').find("[name^='maphanloai_goc']").val(maphanloai_goc).trigger('change');
            $('#frm_them').find("[name^='chitiet']").val(chitiet).trigger('change');
            $('#frm_them').find("[name^='sapxep']").val(sapxep);
            $('#create-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')
@stop
