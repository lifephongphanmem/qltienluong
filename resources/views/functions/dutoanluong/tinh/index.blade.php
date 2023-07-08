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
                    <div class="caption">DANH SÁCH DỰ TOÁN LƯƠNG CỦA SỞ BAN NGÀNH, THÀNH PHỐ, HUYỆN, THỊ XÃ </div>
                    <div class="actions">
                        <button type="button" onclick="inbl_th('{{ $inputs['namns'] }}')" class="btn btn-default mbs">
                            <i class="fa fa-print"></i>&nbsp; In tổng hợp</button>

                        {{-- <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"
                            data-target="#modal-dutoan" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thêm mới dự
                            toán</button> --}}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row" style="padding-bottom: 6px;">
                        <div class="col-md-4 col-md-offset-2">
                            <div class="form-group">
                                <label class="col-md-4 control-label"> Năm dự toán<span class="require">*</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('namns', getNam(), $inputs['namns'], ['id' => 'namns', 'class' => 'form-control select2me']) !!}
                                </div>
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
                                            @if ($value->masodv != null)
                                                <button type="button" title="In số liệu"
                                                    onclick="indutoan('{{ $inputs['namns'] }}','{{ $value->masodv }}',{{$value->madvcq}})"
                                                    class="btn btn-default btn-sm mbs" data-target="#indt-modal"
                                                    data-toggle="modal">
                                                    <i class="fa fa-print"></i>
                                                </button>

                                                <a href="{{ url('/chuc_nang/xem_du_lieu/du_toan/tinh?namns=' . $inputs['namns']) .'&madvbc='. $value->madvbc }}"
                                                    title="Số liệu chi tiết" class="btn btn-default btn-sm">
                                                    <i class="fa fa-list-alt"></i>&nbsp;</a>

                                                <button type="button" title="Trả lại dữ liệu"
                                                    class="btn btn-default btn-sm"
                                                    onclick="confirmChuyen('{{ $value['masodv'] }}')"
                                                    data-target="#tralai-modal" data-toggle="modal">
                                                    <i class="fa icon-share-alt"></i>&nbsp;</button>
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
        <input type="hidden" id="madonvi" name="madv" />
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
                                onclick="insolieu('{{ $furl_th . 'tonghopbienche' }}','1506672780;1506673604;1637915601')"
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
                                onclick="insolieu('{{ $furl_th . 'tonghopbienche_m2' }}', '1506672780;1506673604;1637915601')"
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
                <input type="hidden" name="madv" />
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

    {{-- Trả lại --}}
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url' => '/chuc_nang/du_toan_luong/tinh/tralai', 'id' => 'frm_tralai', 'method' => 'POST']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Lý do trả lại dữ liệu</label>
                    {!! Form::textarea('lydo', null, ['id' => 'lydo', 'class' => 'form-control', 'rows' => '3']) !!}
                </div>
                <input type="hidden" name="masodv" >
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

    <script>
        //Gán thông tin để lấy dữ liệu
        function indutoan(namdt, masodv,madv) {
            $('#namns').val(namdt);
            $('#masodv').val(masodv);
            $('#madonvi').val(madv);
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
            $('#frm_insolieu').find("[name^='madv']").val($('#madonvi').val());


        }

        //In danh sách đơn vi
        function indanhsachdonvi(url) {
            $('#frm_indanhsachdonvi').attr('action', url);
            $('#frm_indanhsachdonvi').find("[name^='masodv']").val($('#masodv').val());
            $('#frm_indanhsachdonvi').find("[name^='namns']").val($('#namns').val());
        }

        function confirmChuyen(masodv) {
            $('#frm_tralai').find("[name^='masodv']").val(masodv);
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

    </script>

@stop
