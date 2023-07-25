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
            $('#namns').change(function() {
                window.location.href = '/chuc_nang/du_toan_luong/tinh/index?namns=' + $('#namns').val();
            });
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH TỔNG HỢP CHI TRẢ LƯƠNG CÁC ĐƠN VỊ BÁO CÁO VÀ ĐƠN VỊ QUẢN LÝ </div>
                    <div class="actions">
                        {{-- <button type="button" onclick="inbl_th('{{ $inputs['namns'] }}')" class="btn btn-default mbs">
                            <i class="fa fa-print"></i>&nbsp; In tổng hợp</button> --}}

                        {{-- <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"
                            data-target="#modal-dutoan" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thêm mới dự
                            toán</button> --}}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row" style="padding-bottom: 6px;">
                        <div class="form-group">
                            <label class="control-label col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thang', getThang(), $inputs['thang'], ['id' => 'thang', 'class' => 'form-control']) !!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('nam', getNam(), $inputs['nam'], ['id' => 'nam', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>


                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Địa bàn báo cáo</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="bold">{{ $value->tendvbc }}</td>
                                        <td class="text-center bold">{{ $a_trangthai[$value['trangthai']] ?? '' }}</td>
                                        <td>
                                            @if ($value->trangthai == 'DAGUI')
                                                <button type="button" title="In số liệu"
                                                    onclick="setInChiLuong('{{ $inputs['thang'] }}','{{ $inputs['nam'] }}','{{ $value->madvcq }}')"
                                                    class="btn btn-default btn-sm mbs" data-target="#inChiLuong-modal"
                                                    data-toggle="modal">
                                                    <i class="fa fa-print"></i>&nbsp;In số liệu
                                                </button>
                                                
                                                <a href="{{ url('/chuc_nang/xem_du_lieu/tinh?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam'] . '&trangthai=ALL&madiaban=' . $value->madvbc) }}"
                                                    class="btn btn-default btn-sm">
                                                    <i class="fa fa-list-alt"></i>&nbsp; Số liệu chi tiết</a>

                                                <button type="button" title="Trả lại dữ liệu"
                                                    class="btn btn-default btn-sm"
                                                    onclick="confirmChuyen('{{ $value['mathdv'] }}','{{ $inputs['thang'] }}','{{ $inputs['nam'] }}')"
                                                    data-target="#tralai-modal" data-toggle="modal">
                                                    <i class="fa icon-share-alt"></i>&nbsp;Trả lại dữ liệu</button>
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


    {{-- Trả lại --}}
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => '/chuc_nang/tong_hop_luong/tinh/tralai', 'id' => 'frm_tralai', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                    <input type="hidden" name="mathdv">
                    <input type="hidden" name="thang">
                    <input type="hidden" name="nam">
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

    <!--Modal in tổng hợp lương -->
    <div id="inChiLuong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="thang_bc" />
        <input type="hidden" id="nam_bc" />
        <input type="hidden" id="macqcq_bc" />
        <input type="hidden" id="madvbc_bc" />
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
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                </div>
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

                <input type="hidden" name="sohieu" />
                <input type="hidden" name="madvbc" />
                <input type="hidden" name="macqcq" />
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function setInChiLuong(thang, nam, madvbc) {
            $('#thang_bc').val(thang);
            $('#nam_bc').val(nam);
            $('#madvbc_bc').val(madvbc);
            $('#macqcq_bc').val(madvbc);
        }

        function insolieu(obj, url) {
            obj.href = url + '?thang=' + $('#thang_bc').val() + '&nam=' + $('#nam_bc').val() + '&macqcq=' + $('#macqcq_bc')
                .val();
        }


        //In danh sách đơn vi
        function indanhsachdonvi(url) {
            $('#frm_indanhsachdonvi').attr('action', url);
            $('#frm_indanhsachdonvi').find("[name^='masodv']").val($('#masodv').val());
            $('#frm_indanhsachdonvi').find("[name^='namns']").val($('#namns').val());
        }

        function confirmChuyen(mathdv, thang, nam) {
            $('#frm_tralai').find("[name^='mathdv']").val(mathdv);
            $('#frm_tralai').find("[name^='thang']").val(thang);
            $('#frm_tralai').find("[name^='nam']").val(nam);

        }

        $(function() {
            $('#frm_tralai :submit').click(function() {
                var chk = true;
                if ($('#lydo').val() == '') {
                    chk = false;
                }

                //Kết quả
                if (chk == false) {
                    toastr.error('Lý do trả lại không được bỏ trống.', 'Lỗi!');
                    $("#frm_tralai").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("#frm_tralai").unbind('submit').submit();
                }
            });
        });

        function getLink() {
            var thang = $('#thang').val();
            var nam = $('#nam').val();
            var madvbc = $('#madvbc').val();
            return '/chuc_nang/tong_hop_luong/tinh/index?thang=' + thang + '&nam=' + nam;
        }

        $(function() {
            $('#thang').change(function() {
                window.location.href = getLink();
            });
            $('#nam').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop
