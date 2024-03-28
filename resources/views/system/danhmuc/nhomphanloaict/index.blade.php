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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <b>NHÓM PHÂN LOẠI CÔNG TÁC</b>
                    </div>
                    {{-- @if (can('dmchucvu', 'create')) --}}
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="addCV()"><i
                                class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                    {{-- @endif --}}
                </div>
                <div class="portlet-body form-horizontal">
                    {{-- <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Phân loại đơn vị</label>
                            <div class="col-md-5">
                                {!! Form::select('mapl', $model_pl, $mapl, ['id' => 'mapl', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div> --}}

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên nhóm phân loại</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $value->stt }}</td>
                                        <td>{{ $value->tennhom }}</td>
                                        <td>{{ $value->phanloai }}</td>
                                        <td>
                                            {{-- @if (can('dmchucvu', 'edit')) --}}
                                                <button type="button" onclick="editCV('{{ $value->manhom }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            {{-- @endif --}}
                                            {{-- @if (can('dmchucvu', 'delete')) --}}
                                                <button type="button" onclick="cfDel('{{ $furl . '/del/' . $value->id }}')"
                                                    class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm"
                                                    data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            {{-- @endif --}}
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
    <form action="/danh_muc/nhomphanloaict/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="phanloai-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Thông tin nhóm phân loại</h4>
                    </div>
                    <input type="hidden" name='edit' id='edit'>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="row">

                                <div class="col-md-12">
                                    <label class="form-control-label">Tên nhóm<span class="require">*</span></label>
                                    {!! Form::text('tennhom', null, ['id' => 'tennhom', 'class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-md-12">
                                    <label class="control-label">Phân loại công tác</label>
                                    <select class="form-control select2me" name="mact[]" id="mact" multiple=true>
                                        @foreach ($model_nhomct as $kieuct)
                                            <optgroup label="{{ $kieuct->tencongtac }}">
                                                <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                                @foreach ($mode_ct as $ct)
                                                    <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="control-label">Sắp xếp</label>
                                    {!! Form::text('stt', $stt, ['id' => 'stt', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" id="submit" name="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="/danh_muc/nhomphanloaict/update" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="phanloai-modal-update" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Thông tin nhóm phân loại</h4>
                    </div>
                    <input type="hidden" name='edit' id='edit'>
                    <input type="hidden" name='manhom' id='manhom_edit'>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="row">

                                <div class="col-md-12">
                                    <label class="form-control-label">Tên nhóm<span class="require">*</span></label>
                                    {!! Form::text('tennhom', null, ['id' => 'tennhom_edit', 'class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-md-12">
                                    <label class="control-label">Phân loại công tác</label>
                                    <select class="form-control select2me" name="mact[]" id="mact_edit" multiple=true>
                                        @foreach ($model_nhomct as $kieuct)
                                            <optgroup label="{{ $kieuct->tencongtac }}">
                                                <?php $mode_ct = $model_tenct->where('macongtac', $kieuct->macongtac); ?>
                                                @foreach ($mode_ct as $ct)
                                                    <option value="{{ $ct->mact }}">{{ $ct->tenct }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="control-label">Sắp xếp</label>
                                    {!! Form::text('stt', null, ['id' => 'stt_edit', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" id="submit" name="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function addCV() {
            $('#phanloai-modal').modal('show');
        }

        function editCV(manhom) {
            $('#phanloai-modal-update').modal('show');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + '/edit',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    manhom: manhom
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $('#tennhom_edit').val(data.tennhom);
                    $('#stt_edit').val(data.stt);
                    $('#manhom_edit').val(data.manhom);
                    $('#mact_edit').val(data.phanloai).trigger('change');
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });

            $('#chucvu-modal').modal('show');
        }

        // function cfCV(manhom) {
        //     var valid = true;
        //     var message = '';
        //     var tennhom = $('#tennhom').val();
        //     var mact = $('#mact').val();
        //     var sapxep = $('#stt').val();
        //     var edit = $('#edit').val();

        //     if (tennhom == '') {
        //         valid = false;
        //         message += 'Tên nhóm không được bỏ trống \n';
        //     }
        //     if (valid) {
        //         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //         if (edit == '') { //Thêm mới
        //             $.ajax({
        //                 url: '{{ $furl }}' + '/store',
        //                 type: 'POST',
        //                 data: {
        //                     _token: CSRF_TOKEN,
        //                     tennhom: tennhom,
        //                     mact: mact,
        //                     stt: sapxep
        //                 },
        //                 dataType: 'JSON',
        //                 success: function(data) {
        //                     console.log(data);
        //                     if (data.status == 'success') {
        //                         location.reload();
        //                     }
        //                 },
        //                 error: function(message) {
        //                     toastr.error(message);
        //                 }
        //             });
        //         } else { //Cập nhật
        //             $.ajax({
        //                 url: '{{ $furl }}' + '/update',
        //                 type: 'POST',
        //                 data: {
        //                     _token: CSRF_TOKEN,
        //                     tennhom: tennhom,
        //                     mact: mact,
        //                     stt: sapxep
        //                 },
        //                 dataType: 'JSON',
        //                 success: function(data) {
        //                     if (data.status == 'success') {
        //                         location.reload();
        //                     }
        //                 },
        //                 error: function(message) {
        //                     toastr.error(message, 'Lỗi!!');
        //                 }
        //             });
        //         }
        //         $('#phongban-modal').modal('hide');
        //     } else {
        //         toastr.error(message, 'Lỗi!.');
        //     }
        // }
    </script>

    @include('includes.modal.delete')
@stop
