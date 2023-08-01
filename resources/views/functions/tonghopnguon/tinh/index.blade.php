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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ</div>
                    <div class="actions">
                        {{-- <a href="{{url($furl.'in_tinh?sohieu=TT67_2017&madiban='.$madvbc)}}" class="btn btn-default btn-sm" TARGET="_blank">
                            <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp ({{$soluong}} đơn vị)</a> --}}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label col-md-6" style="text-align: right">Căn cứ thông tư, quyết định
                                </label>
                                <div class="col-md-6">
                                    {!! Form::select('sohieu', getThongTuQD(false), $inputs['sohieu'], [
                                        'id' => 'sohieu',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <label class="control-label col-md-4" style="text-align: right">Địa bàn, khu vực </label>
                                <div class="col-md-8">
                                    {!! Form::select('madvbc',$a_dvbc,$inputs['madiaban'],array('id' => 'madvbc', 'class' => 'form-control'))!!}
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->tendvbc }}</td>
                                        <td>{{ $a_trangthai[$value->trangthai] }}</td>
                                        <td>
                                            @if ($value->trangthai == 'DAGUI')
                                                {{-- <a href="{{url($furl_th.'tonghop?sohieu='.$value->sohieu.'&madvbc='.$value->madvbc.'&macqcq='.$value->macqcq.'&madv='.$value->madvcq)}}" class="btn btn-default btn-xs" target="_blank">
                                            <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a> --}}
                                                <button type="button" title="In số liệu"
                                                    onclick="innhucau('{{ $value->sohieu }}', '{{ $value->madvbc }}', '{{ $value->madvcq }}')"
                                                    class="btn btn-default btn-sm mbs" data-target="#indt-modal"
                                                    data-toggle="modal">
                                                    <i class="fa fa-print"></i>&nbsp;In số liệu
                                                </button>

                                                <a href="{{ url('/chuc_nang/xem_du_lieu/nguon/tinh?sohieu=' . $value->sohieu . '&trangthai=' . $inputs['trangthai'] . '&madiaban=' . $value->madvbc) }}"
                                                    class="btn btn-default btn-xs">
                                                    <i class="fa fa-print"></i>&nbsp; Số liệu chi tiết</a>

                                                <button type="button" class="btn btn-default btn-xs mbs"
                                                    onclick="confirmChuyen('{{ $value->masodv }}','{{ $value->sohieu }}')"
                                                    data-target="#chuyen-modal" data-toggle="modal"><i
                                                        class="fa icon-share-alt"></i>&nbsp;
                                                    Trả lại dữ liệu</button>
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

    <!--Model trả lại-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => $furl . 'tralai', 'id' => 'frm_chuyen', 'method' => 'POST']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
                    <input type="hidden" name="sohieu" id="sohieu_tl">
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

    <!--Modal thông tin tùy chọn in  -->
    <div id="indt-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <input type="hidden" id="sohieu_in" />
        <input type="hidden" id="madvbc_in" />
        <input type="hidden" id="macqcq_in" />
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'tonghop' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal"
                                title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo phân loại đơn vị của thông tin đơn vị">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng hợp nhu cầu kinh phí (Mẫu 01)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'tonghop_m2' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal"
                                title="Dữ liệu (bao gồm cả cán bộ hợp đồng, không chuyên trách) theo lĩnh vực hoạt động của nhu cầu kinh phí">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng hợp nhu cầu kinh phí (Mẫu 02)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2a' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Mẫu 1)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2a_2' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Mẫu 2)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2a_vn' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng hợp nhu cầu kinh phí (Mẫu 2a - Vạn
                                Ninh)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2b' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu
                                (Mẫu 2b)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2c' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Báo cáo nhu cầu kinh phí thực hiện bảo hiểm thất nghiệp
                                (Mẫu 2c)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2d' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí tăng thêm để thực hiện chế độ cho cán bộ
                                không chuyên trách (Mẫu 2d)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2dd' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Báo cáo nguồn thực hiện CCTL tiết kiệm (Mẫu 2đ)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2e' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Báo cáo nguồn thực hiện CCTL tiết kiệm trong năm (Mẫu
                                2e)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2g' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Báo cáo quỹ tiền lương, phụ cấp đối với lao động theo hợp
                                đồng khu vực hành chính và đơn vị sự nghiệp
                                (Mẫu 2g)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2h' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp phụ cấp ưu đãi giảm do điều chỉnh danh sách huyện
                                nghèo (Mẫu 2h)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2i' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp phụ cấp thu hút giảm do điều chỉnh danh sách
                                huyện nghèo (Mẫu 2i)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2k' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí giảm theo nghị định số 34/2019/NĐ-CP -
                                cán bộ, công chức cấp xã
                                (Mẫu 2k)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau2l' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp kinh phí giảm theo nghị định số 34/2019/NĐ-CP -
                                người hoạt động không chuyên trách
                                (Mẫu 2l)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau4a' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương
                                (Mẫu 4a)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" onclick="insolieu('{{ $furl_th . 'mau4b' }}',null)"
                                style="border-width: 0px" class="btn btn-default btn-xs mbs"
                                data-target="#modal-insolieu" data-toggle="modal">
                                <i class="fa fa-print"></i>&nbsp;Tổng hợp nhu cầu, nguồn thực hiện (Mẫu 4b)</button>
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
        function confirmChuyen(masodv, sohieu) {
            document.getElementById("masodv").value = masodv;
            document.getElementById("sohieu_tl").value = sohieu;
        }

        $(function() {
            $('#frm_chuyen :submit').click(function() {
                var chk = true;
                if ($('#lydo').val() == '') {
                    chk = false;
                }

                //Kết quả
                if (chk == false) {
                    toastr.error('Lý do trả lại không được bỏ trống.', 'Lỗi!');
                    $("#frm_chuyen").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("#frm_chuyen").unbind('submit').submit();
                }
            });
        });

        function getLink() {
            var sohieu = $('#sohieu').val();
            var madvbc = $('#madvbc').val();
            return '/chuc_nang/tong_hop_nguon/tinh/index?sohieu=' + sohieu + '&madiaban=' + madvbc;
        }

        $(function() {
            $('#sohieu').change(function() {
                window.location.href = getLink();
            });

            $('#madvbc').change(function() {
                window.location.href = getLink();
            });
        })

        //Gán thông tin để lấy dữ liệu
        function innhucau(sohieu, madvbc, macqcq) {
            $('#sohieu_in').val(sohieu);
            $('#madvbc_in').val(madvbc);
            $('#macqcq_in').val(macqcq);
        }

        function insolieu(url, mact) {
            // if (mact == null) {
            //     $('#frm_insolieu').find("[name^='mact']").attr('disabled', true);
            // } else {
            //     $('#frm_insolieu').find("[name^='mact']").attr('disabled', false);
            //     $('#frm_insolieu').find("[name^='mact']").val(mact.split(';')).trigger('change');
            // }
            $('#frm_insolieu').attr('action', url);
            $('#frm_insolieu').find("[name^='sohieu']").val($('#sohieu_in').val());
            $('#frm_insolieu').find("[name^='macqcq']").val($('#macqcq_in').val());
            $('#frm_insolieu').find("[name^='madvbc']").val($('#madvbc_in').val());
        }
        
    </script>

@stop
