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
                <div class="caption">DANH SÁCH DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ </div>
                <div class="actions">
                    <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"
                        data-target="#modal-dutoan" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thêm mới dự
                        toán</button>
                </div>
            </div>
            <div class="portlet-body form-horizontal">

                <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Năm ngân sách</th>
                            <th class="text-center">Đơn vị gửi</br>số liệu</th>
                            <th class="text-center">Đơn vị chủ quản</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($model))
                            @foreach ($model as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $value->namns }}</td>
                                    <td class="text-center">{{ $value->dagui . '/' . $value->soluong }}</td>
                                    <td class="text-center">{{ $a_donviql[$value->macqcq] ?? ''}}</td>
                                    <td class="text-center bold">{{ $a_trangthai[$value['trangthai']] ?? ''}}</td>
                                    <td>
                                        <button type="button" title="In số liệu"
                                            onclick="indutoan('{{ $value->namns }}','{{ $value->masodv }}')"
                                            class="btn btn-default btn-sm mbs" data-target="#indt-modal"
                                            data-toggle="modal">
                                            <i class="fa fa-print"></i>
                                        </button>

                                        {{-- <a href="{{ url($furl_th . 'tonghop?namns=' . $value->namns) }}"
                                            class="btn btn-default btn-sm" target="_blank">
                                            <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a> --}}

                                        @if ($value['trangthai'] == 'CHUAGUI')
                                            <button type="button" class="btn btn-default btn-sm" title="Gửi dữ liệu"
                                                onclick="confirmChuyen('{{ $value->namns }}','{{ $value->masodv }}')"
                                                data-target="#chuyen-modal" data-toggle="modal"><i
                                                    class="fa fa-share-square-o"></i>&nbsp;
                                            </button>
                                        @else
                                            <button disabled type="button" class="btn btn-default btn-sm"
                                                title="Gửi dữ liệu"><i class="fa fa-share-square-o"></i>&nbsp;
                                            </button>
                                        @endif

                                        @if ($value['trangthai'] == 'TRALAI')
                                            <button type="button" class="btn btn-default btn-sm" title="Lý do trả lại"
                                                onclick="getLyDo('{{ $value['masodv'] }}')"
                                                data-target="#tralai-modal" data-toggle="modal"><i
                                                    class="fa fa-stack-exchange"></i>&nbsp;
                                            </button>
                                        @endif

                                        <a href="{{ url($furl_xem . '?namns=' . $value->namns . '&trangthai=ALL&phanloai=ALL') }}"
                                            title="Số liệu chi tiết" class="btn btn-default btn-sm">
                                            <i class="fa fa-list-alt"></i>&nbsp;</a>
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


@stop
