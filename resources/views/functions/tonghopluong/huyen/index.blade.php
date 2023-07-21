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
        $(function() {
            $('#nambc').change(function() {
                var nambc = $('#nambc').val();
                var url = '{{ $inputs['furl'] }}' + 'index?nam=' + nambc;
                window.location.href = url;
            });
        })
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        DANH SÁCH DỮ LIỆU TỔNG HỢP LƯƠNG TỪ ĐƠN VỊ TRÊN ĐỊA BÀN
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-1">
                            <div class="form-group">
                                <label class="control-label">Năm dữ liệu </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::select('nambc', getNamTL(), $inputs['nam'], ['id' => 'nambc', 'class' => 'form-control select2me']) !!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Đơn vị</br>gửi số</br>liệu</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @if (isset($model))
                                @foreach ($model as $value)
                                    @if ($value['thang'] != '')
                                        <tr class="{{ getTextStatus($value['trangthai']) }}">
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $value['thang'] . '/' . $inputs['nam'] }}</td>
                                            <td>{{ $value['noidung'] }}</td>
                                            <td class="text-center bold"> {{ $value['dvgui'] . '/' . $value['sldv'] }}</td>
                                            <td class="text-center bold">{{ $a_trangthai[$value['trangthai']] }}</td>
                                            <td>
                                                <button type="button" title="In số liệu"
                                                    onclick="indutoan('{{ $value['thang'] }}', '{{ $inputs['nam'] }}','{{ session('admin')->madv }}')"
                                                    class="btn btn-default btn-sm" data-target="#indt-modal"
                                                    data-toggle="modal">
                                                    <i class="fa fa-print"></i>&nbsp;In số liệu
                                                </button>

                                                @if ($value['mathdv'] != null)
                                                    <!--a href="{{ url($inputs['furl'] . 'tonghop?thang=' . $value['thang'] . '&nam=' . $inputs['nam']) }}" class="btn btn-default btn-xs" target="_blank"-->
                                                    <a href="{{ url('/chuc_nang/tong_hop_luong/khoi/tonghop_khoi?thangbc=' . $value['thang'] . '&nambc=' . $inputs['nam'] . '&madv=' . session('admin')->madv) }}"
                                                        class="btn btn-default btn-sm" TARGET="_blank">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                                    <!--a href="{{ url($inputs['furl'] . 'tonghop_diaban?thang=' . $value['thang'] . '&nam=' . $inputs['nam']) }}" class="btn btn-default btn-xs" target="_blank">
                                                                                                                                    <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a-->
                                                    <a href="{{ url('/chuc_nang/xem_du_lieu/huyen?thang=' . $value['thang'] . '&nam=' . $inputs['nam'] . '&trangthai=ALL&phanloai=ALL') }}"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>
                                                @else
                                                    @if ($value['trangthai'] != 'CHUADL')
                                                        @if ($value['trangthai'] != 'CHUADAYDU')
                                                            <a href="{{ url('/chuc_nang/xem_du_lieu/huyen?thang=' . $value['thang'] . '&nam=' . $inputs['nam'] . '&trangthai=ALL&phanloai=ALL') }}"
                                                                class="btn btn-default btn-xs">
                                                                <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>
                                                            <button type="button" class="btn btn-default btn-sm"
                                                                onclick="confirmChuyen('{{ $value['thang'] }}','{{ $inputs['nam'] }}')"
                                                                data-target="#chuyen-modal" data-toggle="modal"><i
                                                                    class="fa fa-share-square-o"></i>&nbsp;
                                                                Gửi dữ liệu</button>
                                                        @else
                                                            <button type="button" class="btn btn-default btn-sm" disabled
                                                                onclick="confirmChuyen('{{ $value['thang'] }}','{{ $inputs['nam'] }}')"
                                                                data-target="#chuyen-modal" data-toggle="modal"><i
                                                                    class="fa fa-share-square-o"></i>&nbsp;
                                                                Gửi dữ liệu</button>
                                                            <a href="{{ url('/chuc_nang/xem_du_lieu/huyen?thang=' . $value['thang'] . '&nam=' . $inputs['nam'] . '&trangthai=CHOGUI&phanloai=ALL') }}"
                                                                class="btn btn-default btn-sm">
                                                                <i class="fa fa-list-alt"></i>&nbsp; Đơn vị chưa gửi dữ
                                                                liệu</a>
                                                        @endif

                                                        @if ($value['trangthai'] == 'TRALAI')
                                                            <button type="button" class="btn btn-default btn-sm"
                                                                onclick="getLyDo('{{ $value['madvbc'] }}','{{ $value['thang'] }}','{{ $inputs['nam'] }}')"
                                                                data-target="#tralai-modal" data-toggle="modal"><i
                                                                    class="fa fa-share-square-o"></i>&nbsp;
                                                                Lý do trả lại</button>
                                                        @endif
                                                    @else
                                                        <a href="{{ url('/chuc_nang/xem_du_lieu/huyen?thang=' . $value['thang'] . '&nam=' . $inputs['nam'] . '&trangthai=ALL&phanloai=ALL') }}"
                                                            class="btn btn-default btn-sm">
                                                            <i class="fa fa-stack-overflow"></i>&nbsp; Chưa có dữ liệu</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="{{ getTextStatus($value['trangthai']) }}">
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $inputs['nam'] }}</td>
                                            <td>{{ $value['noidung'] }}</td>
                                            <td class="text-center bold"></td>
                                            <td class="text-center bold"></td>
                                            <td>
                                                <a href="{{ url($inputs['furl'] . 'tonghopnam?nam=' . $inputs['nam']) }}"
                                                    class="btn btn-default btn-xs" target="_blank">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                            </td>
                                        </tr>
                                    @endif
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
                {!! Form::open(['url' => $inputs['furl'] . 'senddata', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
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
                    <input type="hidden" name="thang" id="thang">
                    <input type="hidden" name="nam" id="nam">
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

    <!--Model tổng hợp dữ liệu -->
    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => '', 'id' => 'frm_tonghop', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý tổng hợp số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Một số đơn vị cấp dưới chưa gửi dữ liệu tổng hợp.</br>Bạn có chắc chắn muốn tổng hợp số
                                liệu ?</b></label>
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

    <!--Modal in tổng hợp lương -->
    <div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="thang_bc" name="thang" />
        <input type="hidden" id="nam_bc" name="namns" />
        <input type="hidden" id="macqcq_bc" name="macqcq" />
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu</h4>
            </div>
            <div class="modal-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="indanhsachdonvi('{{ $inputs['furl'] . 'danhsachdonvi' }}')"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-indanhsachdonvi" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp; Danh sách đơn vị</button>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a id="in_bl" href="" class="btn btn-default btn-xs mbs"
                                onclick="insolieu(this,'/chuc_nang/tong_hop_luong/huyen/TongHop')"
                                style="border-width: 0px;" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu tổng hợp (mẫu 01)</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a id="in_bl" href="" class="btn btn-default btn-xs mbs"
                                onclick="insolieu(this,'/chuc_nang/tong_hop_luong/huyen/tonghop_huyen')"
                                style="border-width: 0px;" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu tổng hợp (mẫu 02)</a>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                </div>
            </div>
        </div>

        @include('includes.modal.delete')

        <script>
            function confirmChuyen(thang, nam) {
                document.getElementById("thang").value = thang;
                document.getElementById("nam").value = nam;
            }

            function insolieu(obj, url) {
                obj.href = url + '?thang=' + $('#thang_bc').val() + '&nam=' + $('#nam_bc').val() + '&macqcq=' + $('#macqcq_bc')
                    .val();
            }

            function confirmTonghop(url) {
                $('#frm_tonghop').attr('action', url);
            }

            function getLyDo(madvbc, thang, nam) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                console.log(madvbc);
                $.ajax({
                    url: '{{ $inputs['furl'] }}' + 'getlydo',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        madvbc: madvbc,
                        thang: thang,
                        nam: nam
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        // console.log(data);
                        $('#lydo').val(data.lydo);
                    },
                    error: function(message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });

                //$('#madvbc').val(madvbc);
                //$('#phongban-modal').modal('show');
            }

            function indutoan(thang, nam, macqcq) {
                $('#thang_bc').val(thang);
                $('#nam_bc').val(nam);
                $('#macqcq_bc').val(macqcq);
            }
        </script>

    @stop
