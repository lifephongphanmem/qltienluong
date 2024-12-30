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

        function confirmStop(id, ngaytao) {
            document.getElementById("madvstop").value = id;
            document.getElementById("ngaytao").value = ngaytao;

        }

        function confirmActive(id, ngaytao) {
            document.getElementById("madvactive").value = id;
            document.getElementById("ngaytao_hd").value = ngaytao;
        }
        $('#madv').on('change', function() {
            var madv = $('#madv').val();
            window.location.href = '/he_thong/don_vi/stopdv?madv=' + madv;
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ BÁO CÁO VÀ ĐƠN VỊ QUẢN LÝ</div>
                    <div class="actions">
                        @if (session('admin')->sadmin == 'SSA')
                        <button type="button" class="btn btn-default btn-xs" data-target="#chitiet-modal" data-toggle="modal"><i
                            class="fa fa-plus"></i>&nbsp;In nhật ký</button>
                            @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    @if (session('admin')->sadmin == 'SSA')
                        <div class="row" style="padding-bottom: 6px;">
                            <div class="col-md-3 col-md-offset-3">
                                <label class="control-label">Đơn vị chủ quản </label>
                                {!! Form::select('madv', $a_dv_tonghop, $inputs['madv'], ['id' => 'madv', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    @endif
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Ngày tháng dừng/mở HĐ</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td>{{ $value->tendv }}</td>
                                        <td>{{ $value->ngaydung }}</td>
                                        <td>{{ $value->ghichu }}</td>
                                        <td>
                                            @if ($value->trangthai != 'TD')
                                                <button type="button" class="btn btn-default btn-sm"
                                                    onclick="confirmStop('{{ $value['madv'] }}','{{ $value['ngaytao'] }}')"
                                                    data-target="#chuyen-stop" data-toggle="modal"><i
                                                        class="fa icon-share-alt"></i>&nbsp;
                                                    Dừng HĐ</button>
                                            @else
                                                <button class="btn btn-danger btn-xs mbs">
                                                    <i class="fa fa-warning"></i>&nbsp; Đơn vị đã dừng hoạt động</button>
                                                <button type="button" class="btn btn-default btn-sm"
                                                    onclick="confirmActive('{{ $value['madv'] }}','{{ $value['ngaytao'] }}')"
                                                    data-target="#chuyen-active" data-toggle="modal"><i
                                                        class="fa icon-share-alt"></i>&nbsp;
                                                    Hoạt động</button>
                                            @endif
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

    <div class="modal fade" id="chuyen-stop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'stopdv/stop', 'id' => 'frm_stop', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý dừng hoạt động đơn vị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Ngày tạo</b></label>
                        <input type="date" name="ngaytao" id="ngaytao" class="form-control" />
                    </div>
                    <div class="form-group" id="tthschuyen">
                    </div>
                    <div class="form-group">
                        <label><b>Ngày dừng hoạt động</b></label>
                        <input type="date" name="ngaydung" id="ngaydung" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><b>Ghi chú</b></label>
                        <textarea name="ghichu" id="ghichu" cols="10" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="madvstop" id="madvstop">
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
    <div class="modal fade" id="chuyen-active" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'stopdv/active', 'id' => 'frm_active', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý hoạt động đơn vị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Ngày tạo</b></label>
                        <input type="date" name="ngaytao" id="ngaytao_hd" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><b>Ngày hoạt động</b></label>
                        <input type="date" name="ngaydung" id="ngaydung" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><b>Ghi chú</b></label>
                        <textarea name="ghichu" id="ghichu" cols="10" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="madvactive" id="madvactive">
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
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-xs modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin in nhật ký</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed" id="tab_cre">
                                <div class="tab-content">
                                    <!-- Thông tin chung -->
                                    <div class="tab-pane active" id="tab_0_cre">
                                        {!! Form::open([
                                            'url' => '/he_thong/quan_tri/don_vi/nhat_ky',
                                            'target' => '_blank',
                                            'method' => 'post',
                                            'class' => 'form-horizontal form-validate',
                                        ]) !!}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" style="text-align: right">Từ ngày </label>
                                                    {!! Form::date('tungay',null, ['id' => 'tungay', 'class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" style="text-align: right">Đến ngày </label>
                                                    {!! Form::date('denngay', null, ['id' => 'denngay', 'class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="control-label" style="text-align: right">Đơn vị chủ quản
                                                </label>
                                                    {!! Form::select('madv', $a_dv_tonghop_in, null, ['id' => 'madv_th', 'class' => 'form-control select2me']) !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="control-label" style="text-align: right">Trạng thái
                                                </label>
                                                    {!! Form::select('trangthai', $a_trangthai, null, ['id' => 'trangthai_nk', 'class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng
                        ý</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <script>
        // function dungdonvi(madv,ngaytao){
        //     $('#ngaytao').val(ngaytao);
        // }
        // function hddonvi(madv,ngaytao){
        //     $('#ngaytao_hd').val(ngaytao);
        // }
        function getLink() {
            var thang = $('#thang').val();
            var nam = $('#nam').val();
            var trangthai = $('#trangthai').val();
            return '/chuc_nang/xem_du_lieu/huyen?thang=' + thang + '&nam=' + nam + '&trangthai=' + trangthai;
        }

        $(function() {
            $('#thang').change(function() {
                window.location.href = getLink();
            });
            $('#nam').change(function() {
                window.location.href = getLink();
            });
            $('#trangthai').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop
