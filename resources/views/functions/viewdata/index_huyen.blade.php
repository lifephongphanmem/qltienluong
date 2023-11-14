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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ BÁO CÁO VÀ ĐƠN VỊ QUẢN LÝ</div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-1 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thang', getThang(), $thang, ['id' => 'thang', 'class' => 'form-control']) !!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-5">
                                {!! Form::select('nam', getNam(), $nam, ['id' => 'nam', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-default btn-xs" onclick="add()"><i
                                        class="fa fa-plus"></i>&nbsp;In danh sách</button>
                            </div>
                            @if (session('admin')->username == 'cs')
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default btn-xs" onclick="add()"><i
                                            class="fa fa-plus"></i>&nbsp;In tổng hợp</button>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                <div class="col-md-7">
                                    {!! Form::select('trangthai', $a_trangthai, $trangthai, ['id' => 'trangthai', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-md-3" style="text-align: right">Phân loại </label>
                                <div class="col-md-8">
                                    {!! Form::select('phanloai', $a_phanloai, $phanloai, ['id' => 'phanloai', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên đơn vị</th>
                                <!--th class="text-center">Phân loại dữ liệu</th-->
                                <th class="text-center">Phân loại đơn vị</th>
                                <th class="text-center">Lĩnh vực hoạt động</th>
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
                                        <!--td>{{ $value->tenphanloai }}</td-->
                                        <td>{{ $value->tenphanloai }}</td>
                                        <td>{{ $value->linhvuchoatdong }}</td>
                                        <td>
                                            @if ($value->trangthai == 'DAGUI')
                                                @if ($value->phanloaitaikhoan == 'TH')
                                                    <a href="#" data-target="#thkhoi-modal" data-toggle="modal"
                                                        onclick="baocao('{{ '/chuc_nang/tong_hop_luong/khoi/tonghop_khoi' }}','{{ $value['thang'] }}','{{ $nam }}','{{ $value['tendv'] }}','{{ $value['madv'] }}','') "
                                                        class="btn btn-default btn-sm" TARGET="_blank">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp khối</a>
                                                    <a href="#" data-target="#thkhoi-modal" data-toggle="modal"
                                                        onclick="baocao('{{ '/chuc_nang/tong_hop_luong/huyen/chitiet_khoi' }}','{{ $value['thang'] }}','{{ $nam }}','{{ $value['tendv'] }}','{{ $value['madv'] }}','') "
                                                        class="btn btn-default btn-sm" TARGET="_blank">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết khối</a>
                                                @else
                                                    <a href="#" data-target="#thkhoi-modal" data-toggle="modal"
                                                        onclick="baocao('{{ '/chuc_nang/tong_hop_luong/huyen/printf_data_huyen' }}','{{ $value['thang'] }}','{{ $nam }}','{{ $value['tendv'] }}','{{ $value['madv'] }}','{{ $value['mathdv'] }}') "
                                                        class="btn btn-default btn-sm" TARGET="_blank">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>

                                                    <button type="button"
                                                        onclick="inbl('{{ $value['mathdv'] }}','{{ $value['madv'] }}')"
                                                        class="btn btn-default btn-xs mbs">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</button>
                                                @endif
                                               
                                                @if ($value->tralai)
                                                    @if (session('admin')->phamvitonghop == 'KHOI')
                                                        <button type="button" class="btn btn-default btn-sm"
                                                            onclick="confirmChuyen('{{ $value['mathdv'] }}')"
                                                            data-target="#chuyen-modal" data-toggle="modal"><i
                                                                class="fa icon-share-alt"></i>&nbsp;
                                                            Trả lại dữ liệu</button>
                                                    @endif
                                                    @if (session('admin')->phamvitonghop == 'HUYEN')
                                                        <button type="button" class="btn btn-default btn-sm"
                                                            onclick="confirmChuyen('{{ $value['mathh'] }}')"
                                                            data-target="#chuyen-modal" data-toggle="modal"><i
                                                                class="fa icon-share-alt"></i>&nbsp;
                                                            Trả lại dữ liệu</button>
                                                    @endif
                                                @endif
                                            @else
                                                <button class="btn btn-danger btn-xs mbs">
                                                    <i class="fa fa-warning"></i>&nbsp; Đơn vị chưa tổng hợp dữ
                                                    liệu</button>
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

    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'huyen/tralai', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                    <input type="hidden" name="mathdv" id="mathdv">
                    <input type="hidden" name="mathdv" id="mathh">
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
    <div id="inbl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu chi tiết</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_bl" href=""
                                onclick="insolieu('/chuc_nang/tong_hop_luong/huyen/printf_bl_huyen')"
                                style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết</a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blCR" href=""
                                onclick="insolieuCR('/chuc_nang/tong_hop_luong/huyen/printf_bl_huyenCR')"
                                style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết (CR)</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_ttCR" href=""
                                onclick="thanhtoanCR('/chuc_nang/tong_hop_luong/huyen/thanh_toan_CR')"
                                style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In bảng thanh toán lương (CR)</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blkhoito" href=""
                                onclick="inkhoito('/chuc_nang/tong_hop_luong/huyen/inkhoito')"
                                style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết (khối tổ)</a>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="mathdv_in" name="mathdv_in" />
            <input type="hidden" id="madv_in" name="madv_in" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>
    {{-- <div id="tonghop-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
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
                                            'url' => 'chuc_nang/xem_du_lieu/danhsachth',
                                            'target' => '_blank',
                                            'method' => 'post',
                                            'class' => 'form-horizontal form-validate',
                                        ]) !!}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-md-3" style="text-align: right">Năm </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('namth', getNam(), $nam, ['id' => 'namds', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <!--div class="col-md-12">
                                                    <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('trangthai', $a_trangthai, $trangthai, ['id' => 'trangthai', 'class' => 'form-control']) !!}
                                                    </div>
                                                </div-->
                                            <input type="hidden" id="phantramhuong" name="phantramhuong"
                                                value="100" />
                                            <input type="hidden" id="id_ct" name="id_ct" />
                                            <input type="hidden" id="mabl" name="mabl" />
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
            </div>
        </div>
    </div> --}}
    <div id="tonghop-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
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
                                            'url' => 'chuc_nang/xem_du_lieu/danhsach_thh',
                                            'target' => '_blank',
                                            'method' => 'post',
                                            'class' => 'form-horizontal form-validate',
                                        ]) !!}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="col-md-3" style="text-align: right">Tháng</label>
                                                <div class="col-md-9">
                                                   <select name="thang" id="" class="form-control">
                                                    <option value="all">Tất cả các tháng</option>
                                                    @for ($i=1;$i<13;$i++)
                                                        <option value={{$i}}>{{$i}}</option>
                                                    @endfor
                                                   </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-md-3" style="text-align: right">Năm </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('namth', getNam(), $nam, ['id' => 'namds', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <input type="hidden" id="phantramhuong" name="phantramhuong"
                                                value="100" />
                                            <input type="hidden" id="id_ct" name="id_ct" />
                                            <input type="hidden" id="mabl" name="mabl" />
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
            </div>
        </div>
    </div>
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
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
                                            'url' => 'chuc_nang/xem_du_lieu/danhsach',
                                            'target' => '_blank',
                                            'method' => 'post',
                                            'class' => 'form-horizontal form-validate',
                                        ]) !!}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-md-3" style="text-align: right">Tháng </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('thangds', getThang(), $thang, ['id' => 'thangds', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-md-3" style="text-align: right">Năm </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('namds', getNam(), $nam, ['id' => 'namds', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label col-md-3" style="text-align: right">Trạng thái
                                                </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('trangthai', $a_trangthai, $trangthai, ['id' => 'trangthai', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label col-md-3" style="text-align: right"></label>
                                                <input type="checkbox" name="excel" id="excel" />
                                                Xuất dữ liệu ra file excel
                                            </div>
                                            <input type="hidden" id="phantramhuong" name="phantramhuong"
                                                value="100" />
                                            <input type="hidden" id="id_ct" name="id_ct" />
                                            <input type="hidden" id="mabl" name="mabl" />
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
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div id="thkhoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open([
            'url' => '#',
            'target' => '_blank',
            'method' => 'post',
            'id' => 'thoaibc',
            'class' => 'form-horizontal form-validate',
        ]) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất báo cáo</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="col-md-3" style="text-align: right">Tháng: </label>
                                    <div class="col-md-9">
                                        <input class="form-control" id="thangbc" name="thangbc" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="col-md-3" style="text-align: right">Năm: </label>
                                    <div class="col-md-9">
                                        <input class="form-control" id="nambc" name="nambc" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="col-md-3" style="text-align: right">Tên đơn vị: </label>
                                    <div class="col-md-9">
                                        <input class="form-control" id="tendvbc" name="tendvbc" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="control-label col-md-3" style="text-align: right"></label>
                                    <input type="checkbox" name="excelbc" id="excelbc" />
                                    Xuất dữ liệu ra file excel
                                </div>
                                <input type="hidden" id="madv" name="madv" />
                                <input type="hidden" id="mathdvbc" name="mathdvbc" />
                                <input type="hidden" name="urlbc" id="urlbc" value="">
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        {!! Form::close() !!}
    </div>
    <script>
        function baocao(url, thang, nam, tendv, madv, mathdv) {
            $('#urlbc').val(url);
            $('#thangbc').val(thang);
            $('#nambc').val(nam);
            $('#madv').val(madv);
            $('#mathdvbc').val(mathdv);
            $('#tendvbc').val(tendv);
        }
        window.onsubmit = function() {
            document.thoaibc.action = get_action();
        }

        function get_action() {
            var url = $('#urlbc').val();
            $('#thoaibc').attr('action', url);
        }
    </script>
    <script>
        function inbl(mathdv, madv) {
            $("#mathdv_in").val(mathdv);
            $("#madv_in").val(madv);
            $('#inbl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function insolieu($url) {
            $("#in_bl").attr("href", $url + '?mathdv=' + $('#mathdv_in').val() + '&madv=' + $('#madv_in').val());
        }

        function insolieuCR($url) {
            $("#in_blCR").attr("href", $url + '?mathdv=' + $('#mathdv_in').val() + '&madv=' + $('#madv_in').val());
        }

        function thanhtoanCR($url) {
            $("#in_ttCR").attr("href", $url + '?mathdv=' + $('#mathdv_in').val() + '&madv=' + $('#madv_in').val());
        }

        function inkhoito($url) {
            $("#in_blkhoito").attr("href", $url + '?mathdv=' + $('#mathdv_in').val() + '&madv=' + $('#madv_in').val());
        }

        function confirmChuyen(math) {
            document.getElementById("mathdv").value = math;
            document.getElementById("mathh").value = math;
        }

        function getLink() {
            var thang = $('#thang').val();
            var nam = $('#nam').val();
            var trangthai = $('#trangthai').val();
            var phanloai = $('#phanloai').val();
            return '/chuc_nang/xem_du_lieu/huyen?thang=' + thang + '&nam=' + nam + '&trangthai=' + trangthai +
                '&phanloai=' + phanloai;
        }

        function add() {
            $('#chitiet-modal').modal('show');
        }

        function add() {
            $('#tonghop-modal').modal('show');
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
            $('#phanloai').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop
