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
    <link rel="stylesheet" type="text/css" href="{{ url('css/customstyle.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
            $('#printf_mautt107_th').find("[id^='manguonkp']").select2();
            //$('#phucaploaitru').select2();
            //$('#phucapluusotien').select2();
            $("#sapxep").select2();
        });
        var phucaploaitru = '{{ $phucaploaitru }}';
        //$('#phucaploaitru').val("val",phucaploaitru.split(',')).trigger('change');
        var phucapluusotien = '{{ $phucapluusotien }}';
        //$('#phucapluusotien').val("val",phucapluusotien.split(',')).trigger('change');
        //alert(phucapluusotien);
        //$('#phucapluusotien').select2("val",phucapluusotien.split(','));
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH CHI TRẢ LƯƠNG CỦA ĐƠN VỊ</div>
                    <div class="actions">
                        @if ($inputs['thaotac'])
                            <button type="button" class="btn btn-default btn-xs" onclick="add()"><i
                                    class="fa fa-plus"></i>&nbsp;Chi lương
                            </button>

                            <button type="button" class="btn btn-default btn-xs" onclick="add_truylinh()"><i
                                    class="fa fa-plus"></i>&nbsp;Truy lĩnh
                            </button>

                            <button type="button" class="btn btn-default btn-xs" data-target="#modal-TaoThuyetMinh"
                                data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thuyết minh
                            </button>

                            <div class="btn-group btn-group-solid">
                                <button type="button" class="btn btn-lg btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="true">
                                    Chi trả khác <i class="fa fa-angle-down"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="btn btn-default"
                                            style="padding-bottom: 5px; border: none;" onclick="add_truc()">&nbsp;Chi ngày
                                            làm việc</button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn btn-default"
                                            style="padding-bottom: 5px; border: none;" onclick="add_ctp()"
                                            data-target="#ctphi-modal" data-toggle="modal">&nbsp;Công tác phí</button>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row" style="padding-bottom: 6px;">
                        <div class="col-md-3 col-md-offset-3">
                            <label class="control-label">Tháng </label>
                            {!! Form::select('thangct', getThang(), $inputs['thang'], ['id' => 'thangct', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-3">
                            <label class="control-label">Năm </label>
                            {!! Form::select('namct', getNam(), $inputs['nam'], ['id' => 'namct', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-3 text-right">
                            <button type="button" onclick="inbl_th('{{ $inputs['thang'] }}','{{ $inputs['nam'] }}')"
                                class="btn btn-default mbs">
                                <i class="fa fa-print"></i>&nbsp; In tổng hợp</button>
                        </div>

                    </div>

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Nguồn kinh phí</th>
                                <th class="text-center">Lĩnh vực<br>hoạt động</th>
                                <th class="text-center">Nội dung<br>bảng lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <?php $i = 1; ?>
                        <tbody>
                            @foreach ($model as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ isset($a_phanloai[$value->phanloai]) ? $a_phanloai[$value->phanloai] : 'Bảng lương cán bộ' }}
                                    </td>
                                    <td>{{ isset($a_nguonkp_bl[$value->manguonkp]) ? $a_nguonkp_bl[$value->manguonkp] : '' }}
                                    </td>
                                    <td>{{ isset($m_linhvuc[$value->linhvuchoatdong]) ? $m_linhvuc[$value->linhvuchoatdong] : '' }}
                                    </td>
                                    <td>{{ $value->noidung }}</td>
                                    <td>
                                        @if ($inputs['thaotac'])
                                            @if ($value->phanloai == 'BANGLUONG')
                                                <div class="btn-group btn-group-solid">
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-xs"
                                                        data-toggle="dropdown" aria-expanded="true">
                                                        <i class="fa fa-cog"></i> Chức năng <i class="fa fa-angle-down"></i>
                                                    </button>

                                                    <ul class="dropdown-menu" style="margin-top: 0px;position: static">
                                                        <li>
                                                            <button onclick="capnhat('{{ $value->mabl }}')"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn btn-default" data-target="#capnhat-modal-confirm"
                                                                data-toggle="modal">
                                                                <i class="fa fa-caret-right"></i>Cập nhật lương</button>
                                                        </li>
                                                        <li>
                                                            <button onclick="trichnop('{{ $value->mabl }}')"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn btn-default"
                                                                data-target="#trichnop-modal-confirm" data-toggle="modal">
                                                                <i class="fa fa-caret-right"></i>Trích nộp lương</button>
                                                        </li>
                                                        <li>
                                                            <button
                                                                onclick="capnhat_nkp('{{ $value->mabl }}', '{{ $value->manguonkp }}')"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn btn-default" data-target="#capnhat_nkp-modal"
                                                                data-toggle="modal">
                                                                <i class="fa fa-caret-right"></i>Cập nhật nguồn kinh
                                                                phí</button>
                                                        </li>
                                                        <li>
                                                            <button onclick="capnhat_khenthuong('{{ $value->mabl }}')"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn btn-default" data-target="#capnhat_nkp-modal"
                                                                data-toggle="modal">
                                                                <i class="fa fa-caret-right"></i>Khen thưởng / Giảm trừ
                                                                lương</button>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url($inputs['furl'] . 'ThuyetMinhChiTiet?mabl=' . $value->mabl) }}"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn-default">
                                                                <i class="fa fa-caret-right"></i>Thuyết minh chi tiết</a>
                                                        </li>
                                                        {{-- <li>
                                                            <a href="{{ url($inputs['furl'] . 'ThemCanBo?mabl=' . $value->mabl) }}"
                                                                style="border: none;padding-top: 0px;padding-bottom: 0px;"
                                                                class="btn-default">
                                                                <i class="fa fa-caret-right"></i>Thêm cán bộ</a>
                                                        </li> --}}

                                                    </ul>
                                                </div>

                                                <button type="button"
                                                    onclick="inbl('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp; In bảng lương</button>
                                            @elseif($value->phanloai == 'TRUYLINH')
                                                <button type="button"
                                                    onclick="inbl_tl('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp; In bảng lương</button>
                                            @elseif($value->phanloai == 'THUYETMINH')
                                                <a href="{{ url($inputs['furl'] . 'ThuyetMinhChiTiet?thang=' . $value->thang . '&nam=' . $value->nam) }}"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp;Sửa
                                                </a>

                                                <button type="button"
                                                    onclick="cfDel('{{ '/chuc_nang/bang_luong/XoaThuyetMinh/' . $value->id }}')"
                                                    class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm"
                                                    data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp;Xóa
                                                </button>
                                            @else
                                                <button type="button"
                                                    onclick="inbl_khac('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp;In chi tiết</button>
                                            @endif
                                            <!--button onclick="tanggiam('{{ $value->mabl }}')" class="btn btn-default btn-xs mbs" data-target="#tanggiam-modal-confirm" data-toggle="modal">
                                                                                        <i class="fa fa-th-list"></i>&nbsp; Tăng/Giảm lương</button-->
                                            {{--                                            @elseif($value->phanloai == 'TRUC') --}}
                                            {{--                                                <a href="{{url($inputs['furl'].'mautruc?mabl='.$value->mabl)}}" class="btn btn-default btn-xs mbs" target="_blank"> --}}
                                            {{--                                                    <i class="fa fa-print"></i>&nbsp; In chi tiết</a> --}}
                                            {{--                                            @else --}}
                                            {{--                                                <a href="{{url($inputs['furl'].'mauctphi?mabl='.$value->mabl)}}" class="btn btn-default btn-xs mbs" target="_blank"> --}}
                                            {{--                                                    <i class="fa fa-print"></i>&nbsp; In chi tiết</a> --}}
                                            {{--                                            @endif --}}

                                            @if ($value->phanloai != 'THUYETMINH')
                                                <button type="button"
                                                    onclick="edit('{{ $value->mabl }}','{{ $value->phanloai }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp;Sửa
                                                </button>

                                                <a href="{{ url($inputs['furl'] . 'bang_luong?mabl=' . $value->mabl . '&mapb=') }}"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-th-list"></i>&nbsp;Chi tiết
                                                </a>
                                                <button type="button"
                                                    onclick="cfDel('{{ $inputs['furl'] . 'del/' . $value->id }}')"
                                                    class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm"
                                                    data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp;Xóa
                                                </button>
                                            @endif
                                        @else
                                            @if ($value->phanloai == 'BANGLUONG')
                                                <button type="button"
                                                    onclick="inbl('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp;In bảng lương</button>
                                            @elseif($value->phanloai == 'TRUYLINH')
                                                <button type="button"
                                                    onclick="inbl_tl('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp;In bảng lương</button>
                                            @else
                                                <button type="button"
                                                    onclick="inbl_khac('{{ $value->mabl }}','{{ $value->thang }}','{{ $value->nam }}')"
                                                    class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp;In chi tiết</button>
                                            @endif
                                            {{--                                            @elseif($value->phanloai == 'TRUC') --}}
                                            {{--                                                <a href="{{url($inputs['furl'].'mautruc?mabl='.$value->mabl)}}" class="btn btn-default btn-xs mbs" target="_blank"> --}}
                                            {{--                                                    <i class="fa fa-print"></i>&nbsp; In chi tiết</a> --}}
                                            {{--                                            @else --}}
                                            {{--                                                <a href="{{url($inputs['furl'].'mauctphi?mabl='.$value->mabl)}}" class="btn btn-default btn-xs mbs" target="_blank"> --}}
                                            {{--                                                    <i class="fa fa-print"></i>&nbsp; In chi tiết</a> --}}
                                            {{--                                            @endif --}}
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal chức năng -->
    @include('manage.bangluong.modal_chucnang')
    <!--Modal in bảng lương lương -->
    @include('manage.bangluong.modal_indl')
    <!--Modal thoại -->
    @include('templates.modal_printf_luong')
    @include('templates.modal_printf_th_luong')

    <!--Modal Thuyết minh -->
    @include('manage.bangluong.modal_ThuyetMinh')
    <script>
        function disable_btn(obj) {
            obj.prop('disabled', true);
        }

        function capnhat(mabl) {
            $('#mabl_capnhat').val(mabl);
        }

        function capnhat_nkp(mabl, manguonkp) {
            $('#frmcapnhat_nkp').find("[name='mabl']").val(mabl);
            $('#frmcapnhat_nkp').find("[id='manguonkp']").val(manguonkp).trigger('change');
        }

        function capnhat_khenthuong(mabl) {
            $('#frm_khenthuong').find("[name='mabl']").val(mabl);
        }

        function trichnop(mabl) {
            $('#create_trichnop').find("[id='phanloai']").val('TRICHNOP');
            $('#create_trichnop').find("[id='mabl_trichnop']").val(mabl);
            $('#create_trichnop').find("[id='mabl']").val('NULL'); //them moi
            $('#mabl_capnhat').val(mabl);
        }

        function tanggiam(mabl) {
            $('#mabl_tg').val(mabl);
        }

        function getLink() {
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{ $inputs['furl'] }}' + 'chi_tra?thang=' + thang + '&nam=' + nam;
        }

        function add() {
            $('#luongcoban').prop('readonly', false); //mặc định
            $('#manguonkp').prop('disabled', false);
            $('#phucaploaitru').prop('disabled', false);

            var dm = '{{ $inputs['dinhmuc'] }}';
            $('#noidung').val('');
            //$('#phantramhuong').val(100);
            $('#phanloai').val('BANGLUONG');
            $('#mabl').val('');
            $('#id_ct').val(0);
            if (dm == 1) { //do khi đm = 0 (false) ct set = "readonly" ~ readonly = true =>sai
                $('#luongcoban').prop('readonly', true);
            }

            $('#chitiet-modal').modal('show');
            //$("#tab_cre").tabs("enable", 1);
        }

        function add_truylinh() {
            $('#phanloai_truylinh').val('TRUYLINH');
            $('#noidung_truylinh').val('');
            $('#mabl_truylinh').val('');
            $('#truylinh-modal').modal('show');
        }

        function add_truc() {
            $('#phanloai_truc').val('TRUC');
            $('#noidung_truc').val('');
            $('#mabl_truc').val('');
            $('#truc-modal').modal('show');
        }

        function add_ctp() {
            //$('#phanloai_truc').val('CTP');
            //$('#noidung_truc').val('');
            $('#create_ctp').find("[id='phanloai']").val('CTPHI');
            $('#create_ctp').find("[id='mabl']").val('');
            //$('#chikhac-modal').modal('show');
        }

        function confirm_trichnop() {
            var str = '';
            var ok = true;

            if ($('#create_trichnop').find("[id='tenquy']").val() == '') {
                str += '  - Tên quỹ \n';
                ok = false;
            }

            switch ($('#phanloai_pptinh').val()) {
                case 'sotien': {
                    if ($('#create_trichnop').find("[id='sotien']").val() == '') {
                        str += '  - Số tiền trích nộp \n';
                        ok = false;
                    }
                    break;
                }
                case 'ngaycong': {
                    if ($('#create_trichnop').find("[id='ngaycong']").val() == '') {
                        str += '  - Số ngày công trích nộp \n';
                        ok = false;
                    }

                    if ($('#create_trichnop').find("[id='tongngaycong']").val() == '') {
                        str += '  - Tổng số ngày công \n';
                        ok = false;
                    }
                    break;
                }
                case 'phantram': {
                    if ($('#create_trichnop').find("[id='phantram']").val() == '') {
                        str += '  - Phần trăm trích nộp \n';
                        ok = false;
                    }
                    break;
                }
            }

            if (!ok) {
                alert('Các trường: \n' + str + 'Không được để trống');
                $('#create_trichnop').submit(function(e) {
                    e.preventDefault();
                });
            } else {
                $('#create_trichnop').unbind('submit').submit();
                $('#trichnop-modal-confirm').modal('hide');
            }
        }

        function edit(mabl, phanloai) {
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            switch (phanloai) {
                case 'TRUYLINH': {
                    $.ajax({
                        url: '{{ $inputs['furl_ajax'] }}' + 'get',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mabl: mabl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            $('#thang_truylinh').val(data.thang);
                            $('#nam_truylinh').val(data.nam);
                            $('#noidung_truylinh').val(data.noidung);
                            //$('#manguonkp_truylinh').val(data.manguonkp);
                            //$('#luongcoban_truylinh').val(data.luongcoban);
                            $('#linhvuchoatdong_truylinh').val(data.linhvuchoatdong).trigger('change');
                            $('#mabl_truylinh').val(data.mabl);
                            $('#phanloai_truylinh').val(data.phanloai);
                            $('#ngaylap_truylinh').val(data.ngaylap);
                            $('#nguoilap_truylinh').val(data.nguoilap);
                        },
                        error: function(message) {
                            toastr.error(message, 'Lỗi!');
                        }
                    });
                    $('#truylinh-modal').modal('show');
                    break;
                }

                case 'TRICHNOP': {
                    var form_ctp = $('#create_trichnop');
                    $.ajax({
                        url: '{{ $inputs['furl_ajax'] }}' + 'get',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mabl: mabl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            form_ctp.find("[id^='noidung']").val(data.noidung);
                            form_ctp.find("[id^='mabl']").val(data.mabl);
                            form_ctp.find("[id^='phanloai']").val(data.phanloai);
                            form_ctp.find("[id^='ngaylap']").val(data.ngaylap);
                            form_ctp.find("[id^='nguoilap']").val(data.nguoilap);
                            form_ctp.find("[id^='tenquy']").val(data.tenquy);
                        },
                        error: function(message) {
                            toastr.error(message, 'Lỗi!');
                        }
                    });
                    $('#trichnop-modal-confirm').modal('show');
                    break;
                }

                case 'BANGLUONG': {
                    $.ajax({
                        url: '{{ $inputs['furl_ajax'] }}' + 'get',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mabl: mabl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.phucaploaitru != null) {
                                var a_pc = data.phucaploaitru.split(',');
                                $('#phucaploaitru').select2("val", a_pc);
                            }
                            $('#thang').val(data.thang);
                            $('#nam').val(data.nam);
                            $('#noidung').val(data.noidung);
                            $('#manguonkp').val(data.manguonkp);
                            $('#linhvuchoatdong').val(data.linhvuchoatdong).trigger('change');
                            $('#phantramhuong').val(data.phantramhuong);
                            $('#luongcoban').val(data.luongcoban);
                            $('#mabl').val(data.mabl);
                            $('#phanloai').val(data.phanloai);
                            $('#ngaylap').val(data.ngaylap);
                            $('#nguoilap').val(data.nguoilap);
                        },
                        error: function(message) {
                            toastr.error(message, 'Lỗi!');
                        }
                    });
                    $('#chitiet-modal').modal('show');

                    $('#luongcoban').prop('readonly', true);
                    $('#manguonkp').prop('disabled', true);
                    $('#phucaploaitru').prop('disabled', true);
                    //$("#tab_cre" ).tabs( { disabled: [1] } );
                    break;
                }

                case 'CTPHI': {
                    var form_ctp = $('#create_ctp');
                    $.ajax({
                        url: '{{ $inputs['furl_ajax'] }}' + 'get',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mabl: mabl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            form_ctp.find("[id^='noidung']").val(data.noidung);
                            form_ctp.find("[id^='mabl']").val(data.mabl);
                            form_ctp.find("[id^='phanloai']").val(data.phanloai);
                            form_ctp.find("[id^='ngaylap']").val(data.ngaylap);
                            form_ctp.find("[id^='nguoilap']").val(data.nguoilap);
                        },
                        error: function(message) {
                            toastr.error(message, 'Lỗi!');
                        }
                    });
                    $('#ctphi-modal').modal('show');
                    break;
                }

                case 'TRUC': {
                    $.ajax({
                        url: '{{ $inputs['furl_ajax'] }}' + 'get',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            mabl: mabl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            $('#noidung_truc').val(data.noidung);
                            $('#manguonkp_truc').val(data.manguonkp);
                            $('#linhvuchoatdong_truc').val(data.linhvuchoatdong).trigger('change');
                            $('#luongcoban_truc').val(data.luongcoban);
                            $('#mabl_truc').val(data.mabl);
                            $('#ngaylap_truc').val(data.ngaylap);
                            $('#nguoilap_truc').val(data.nguoilap);
                            $('#phanloai_truc').val(data.phanloai);
                        },
                        error: function(message) {
                            toastr.error(message, 'Lỗi!');
                        }
                    });
                    $('#truc-modal').modal('show');
                    break;
                }

                default: {
                    // do something
                }
            }


            //$('#tab_cre').tabs();
        }

        function taobl(mabl) {
            //var tr = $(e).closest('tr');
            $('#chitiet-modal').modal('hide');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //create_bangluong
            var form = $('#create_bangluong');
            $.ajax({
                url: '{{ $inputs['furl'] }}' + 'store_mau',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    thang: $('#thang').val(),
                    nam: $('#nam').val(),
                    dinhmuc: $('#dinhmuc').val(),
                    phantramhuong: $('#phantramhuong').val(),
                    ngaylap: $('#ngaylap').val(),
                    nguoilap: $('#nguoilap').val(),
                    linhvuchoatdong: $('#linhvuchoatdong').val(),
                    mabl: mabl,
                    manguonkp: $('#manguonkp').val(),
                    luongcoban: $('#luongcoban').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 'success') {
                        location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(message) {
                    toastr.error(message);
                }
            });
        }

        function inbl(mabl, thang, nam) {
            document.getElementById("hd-inbl").innerHTML = "In bảng lương tháng " + thang + ' năm ' + nam;
            $("#mabl_in").val(mabl);
            $("#thang_in").val(thang);
            $("#nam_in").val(nam);
            $('#inbl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function inbl_tl(mabl, thang, nam) {
            document.getElementById("hd-inbl_tl").innerHTML = "In bảng truy lĩnh lương tháng " + thang + ' năm ' + nam;
            //gán giá trị vào modal in để lấy giá trị
            $("#mabl_in").val(mabl);
            $("#thang_in").val(thang);
            $("#nam_in").val(nam);
            $('#inbl_tl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function inbl_khac(mabl, thang, nam) {
            document.getElementById("hd-inbl_khac").innerHTML = "In bảng chi trả các loại phụ cấp tháng " + thang +
                ' năm ' + nam;
            //gán giá trị vào modal in để lấy giá trị
            $("#mabl_in").val(mabl);
            $("#thang_in").val(thang);
            $("#nam_in").val(nam);
            $('#inbl_khac-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function inbl_th(mabl, thang, nam) {
            //document.getElementById("hd-inbl").innerHTML="In bảng lương tháng " + thang + ' năm ' + nam;
            $('#inbl_th-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function setUrl(tag, url) {
            tag.href = url + '?mabl=' + $('#mabl_in').val();
        }

        function incd() {
            $("#in_cd").attr("href", '/chuc_nang/bang_luong/maucd?mabl=' + $('#mabl_in').val());
        }

        function inmc() {
            $("#in_mc").attr("href", '/chuc_nang/bang_luong/maumc?mabl=' + $('#mabl_in').val());
        }

        function intruc() {
            $("#in_truc").attr("href", '/chuc_nang/bang_luong/mautruc?mabl=' + $('#mabl_in').val());
        }

        function intn() {
            $("#in_tn").attr("href", '/chuc_nang/bang_luong/mautinhnguyen?mabl=' + $('#mabl_in').val());
        }

        function inthpc() {
            $("#in_thpc").attr("href", '/chuc_nang/bang_luong/mauthpc?mabl=' + $('#mabl_in').val());
        }

        function inthpl() {
            $("#in_thpl").attr("href", '/chuc_nang/bang_luong/mauthpl?mabl=' + $('#mabl_in').val());
        }

        function inmau09_KH() {
            $("#in_mau09").attr("href", '/chuc_nang/bang_luong/mau09_KH?mabl=' + $('#mabl_in').val());
        }

        $(function() {
            $("input[type=radio][name=pptinh]").change(function() {
                //alert($(this).val());
                switch ($(this).val()) {
                    case 'sotien': {
                        $('#create_trichnop').find("[id='sotien']").prop('readonly', false);
                        $('#create_trichnop').find("[id='ngaycong']").prop('readonly', true);
                        $('#create_trichnop').find("[id='tongngaycong']").prop('readonly', true);
                        $('#create_trichnop').find("[id='phantram']").prop('readonly', true);
                        $('#create_trichnop').find("[id='phantramtinh']").prop('readonly', true);
                        $('#phanloai_pptinh').val('sotien');
                        break;
                    }
                    case 'ngaycong': {
                        $('#create_trichnop').find("[id='ngaycong']").prop('readonly', false);
                        $('#create_trichnop').find("[id='tongngaycong']").prop('readonly', false);
                        $('#create_trichnop').find("[id='phantramtinh']").prop('readonly', false);
                        $('#create_trichnop').find("[id='sotien']").prop('readonly', true);
                        $('#create_trichnop').find("[id='phantram']").prop('readonly', true);
                        $('#phanloai_pptinh').val('ngaycong');
                        break;
                    }
                    case 'phantram': {
                        $('#create_trichnop').find("[id='phantram']").prop('readonly', false);
                        $('#create_trichnop').find("[id='sotien']").prop('readonly', true);
                        $('#create_trichnop').find("[id='ngaycong']").prop('readonly', true);
                        $('#create_trichnop').find("[id='tongngaycong']").prop('readonly', true);
                        $('#create_trichnop').find("[id='phantramtinh']").prop('readonly', true);
                        $('#phanloai_pptinh').val('phantram');
                        break;
                    }
                }

            });

            $('#thangct').change(function() {
                window.location.href = getLink();
            });

            $('#namct').change(function() {
                window.location.href = getLink();
            });

            $(".disabled").click(function(e) {
                e.preventDefault();
                return false;
            });

            $('#manguonkp').change(function() {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ $inputs['furl_ajax'] }}' + 'get_nguonkp',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        manguonkp: $('#manguonkp').val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        $('#luongcoban').prop('readonly', false);
                        $('#luongcoban').val(data['luongcb']);
                        $('#dinhmuc').val(data['dinhmuc']);
                        $('#luongcoban').prop('readonly', data['dinhmuc']);
                    },
                    error: function(message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });
            });

            $('#create_bangluong :submit').click(function() {
                var ok = true,
                    message = '';
                var thang = $('#thang').val();
                var nam = $('#nam').val();

                if (thang == null) {
                    ok = false;
                    message += 'Tháng bảng lương không được bỏ trống. \n';
                }
                if (nam == null) {
                    ok = false;
                    message += 'Năm bảng lương không được bỏ trống. \n';
                }

                //Kết quả
                if (ok == false) {
                    toastr.error(message, "Lỗi!");
                    $("create_bangluong").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("create_bangluong").unbind('submit').submit();
                }
            });
        });
    </script>
    @include('includes.modal.delete')
@stop
