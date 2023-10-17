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
                                        <td class="text-center">{{ $a_donviql[$value->macqcq] ?? '' }}</td>
                                        <td class="text-center bold">{{ $a_trangthai[$value['trangthai']] ?? '' }}</td>
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

                                            @if (in_array($value['trangthai'], ['CHUAGUI', 'TRALAI']))
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
                                                    onclick="getLyDo('{{ $value['masodv'] }}')" data-target="#tralai-modal"
                                                    data-toggle="modal"><i class="fa fa-stack-exchange"></i>&nbsp;
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

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl_th . 'senddata', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi
                                gửi.</b></label>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" style="text-align: right">Đơn vị nhận dữ liệu </label>
                            {!! Form::select('macqcq', $a_donviql, null, [
                                'id' => 'trangthai',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                    <input type="hidden" name="namns" id="namns">
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

    {!! Form::open(['url' => $furl_th . 'tao_du_toan', 'id' => 'frm_dutoan', 'class' => 'form-horizontal']) !!}
    <div id="modal-dutoan" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chi tiết</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Năm dự toán<span class="require">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select('namns', getNam(), date('Y'), ['id' => 'namns', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <input type="hidden" id="madv" name="madv" value="{{ session('admin')->madv }}" />
                    </div>
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

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="namns" name="namns" />
        <input type="hidden" id="masodv" name="masodv" />
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="indanhsachdonvi('{{ $furl_th . 'danhsachdonvi' }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-indanhsachdonvi" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Danh sách đơn vị</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'kinhphikhongchuyentrach' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp kinh phí thực hiện
                                chế đố phụ cấp cán bộ không chuyên trách</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl_th . 'tonghopcanboxa' }}','1506672780;1506673604;1637915601')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp cán bộ chuyên trách,
                                công chức xã</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl_th . 'tonghopcanbohdnd' }}','1536402868;1536402870;1536459380;1536459382;1558600713;1558945077')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp đại biểu HĐND; cấp uỷ viên</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl_th . 'tonghopbienche' }}','1506672780;1506673604;1637915601;1534990562')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp biên chế, hệ số
                                lương và phụ cấp có mặt (Mẫu 01)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl_th . 'tonghopbienche_m2' }}', '1506672780;1506673604;1637915601;1534990562')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp biên chế, hệ số
                                lương và phụ cấp có mặt (Mẫu 02)</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'tonghophopdong' }}','1506673585')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp hợp đồng bổ sung quỹ lương (Mẫu 01)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button"
                                onclick="insolieu('{{ $furl_th . 'tonghophopdong_m2' }}','1506673585')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Tổng hợp hợp đồng bổ sung quỹ lương (Mẫu 02)</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>


    <!--Mẫu in số liệu -->
    {!! Form::open(['url' => '', 'method' => 'post', 'target' => '_blank', 'files' => true, 'id' => 'frm_insolieu']) !!}
    <div id="modal-insolieu" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
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
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                </div>

                <input type="hidden" name="masodv" />
                <input type="hidden" name="namns" />
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- Mẫu in danh sách đơn vị --}}
    {!! Form::open([
        'url' => '',
        'method' => 'post',
        'target' => '_blank',
        'files' => true,
        'id' => 'frm_indanhsachdonvi',
    ]) !!}
    <div id="modal-indanhsachdonvi" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" style="text-align: right">Phân loại </label>
                            {!! Form::select('phanloai', $a_phanloai, null, ['id' => 'phanloai', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" style="text-align: right">Trạng thái </label>
                            {!! Form::select('trangthai', $a_trangthai_in, null, [
                                'id' => 'trangthai',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Đơn vị tính</label>
                            {!! Form::select('donvitinh', getDonViTinh(), null, ['class' => 'form-control select2me']) !!}
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Cỡ chữ</label>
                            {!! Form::select('cochu', getCoChu(), 10, ['id' => 'cochu', 'class' => 'form-control select2me']) !!}
                        </div>
                    </div>
                </div>

                <input type="hidden" name="masodv" />
                <input type="hidden" name="namns" />
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

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
        //Gán thông tin để lấy dữ liệu
        function indutoan(namdt, masodv) {
            $('#namns').val(namdt);
            $('#masodv').val(masodv);
        }

        //In dữ liệu
        function insolieu(url, mact) {
            if (mact == null) {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', true);
            } else {
                $('#frm_insolieu').find("[name^='mact']").attr('disabled', false);
                $('#frm_insolieu').find("[name^='mact']").val(mact.split(';')).trigger('change');
            }
            $('#frm_insolieu').attr('action', url);
            $('#frm_insolieu').find("[name^='masodv']").val($('#masodv').val());
            $('#frm_insolieu').find("[name^='namns']").val($('#namns').val());
        }

        //In danh sách đơn vi
        function indanhsachdonvi(url) {
            $('#frm_indanhsachdonvi').attr('action', url);
            $('#frm_indanhsachdonvi').find("[name^='masodv']").val($('#masodv').val());
            $('#frm_indanhsachdonvi').find("[name^='namns']").val($('#namns').val());
        }

        function confirmChuyen(namns, masodv) {
            $('#frm_chuyen').find("[name^='namns']").val(namns);
            $('#frm_chuyen').find("[name^='masodv']").val(masodv);
        }

        function getLyDo(masodv) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/du_toan_luong/huyen/getlydo',
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

            //$('#madvbc').val(madvbc);
            //$('#phongban-modal').modal('show');
        }
    </script>

@stop
