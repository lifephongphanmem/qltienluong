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
                    <div class="caption">
                        <b>DANH SÁCH CÁN BỘ TẠM NGỪNG THEO DÕI</b>
                    </div>
                    <div class="actions">

                        <div class="btn-group btn-group-solid col-md-pull-6">
                            <button type="button" class="btn btn-lg btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="true">
                                Thêm mới <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=THAISAN') }}" class="btn btn-default btn-xs">
                                        Nghỉ thai sản</a>
                                </li>
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=DUONGSUC') }}" class="btn btn-default btn-xs">
                                        Nghỉ dưỡng sức</a>
                                </li>
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=NGHIPHEP') }}" class="btn btn-default btn-xs">
                                        Nghỉ ốm/Nghỉ phép</a>
                                </li>
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=DAINGAY') }}" class="btn btn-default btn-xs">
                                        Nghỉ dài ngày</a>
                                </li>
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=KHONGLUONG') }}"
                                        class="btn btn-default btn-xs">
                                        Nghỉ không lương</a>
                                </li>
                                <li>
                                    <a href="{{ url($furl . 'create?phanloai=KYLUAT') }}" class="btn btn-default btn-xs">
                                        Kỷ luật</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Thông tin</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($model))
                                @foreach ($model as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->tencanbo }}</td>
                                        <td>{{ $value->phanloai }}</td>
                                        @switch($value->maphanloai)
                                            @case ('KHONGLUONG')

                                            @case ('DAINGAY')

                                            @case ('THAISAN')
                                                <td>Từ {{ getDayVn($value->ngaytu) }} đến {{ getDayVn($value->ngayden) }}</td>
                                            @break

                                            @case ('DUONGSUC')

                                            @case ('NGHIOM')

                                            @case ('NGHIPHEP')
                                                <td>Nghỉ từ ngày: {{ getDayVn($value->ngaytu) }} - Số ngày nghỉ:
                                                    {{ $value->songaynghi }}
                                                    <br>Ngày thanh toán: {{ getDayVn($value->ngaythanhtoan) }}
                                                </td>
                                            @break

                                            @case ('KYLUAT')
                                                <td>Từ {{ getDayVn($value->ngaytu) }} đến {{ getDayVn($value->ngayden) }}</td>
                                            @break

                                            @default
                                                <td></td>
                                        @endswitch

                                        <td>
                                            <a href="{{ url($furl . 'edit?maso=' . $value->maso . '') }}"
                                                class="btn btn-default btn-xs mbs"><i class="fa fa-edit"></i>&nbsp;Sửa
                                            </a>

                                            <button type="button" onclick="cfDel('{{ $furl . 'del/' . $value->id }}')"
                                                class="btn btn-default btn-xs" data-target="#delete-modal-confirm"
                                                data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp;Xóa
                                            </button>
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
    {!! Form::open(['url' => '/nghiep_vu/tam_ngung/store', 'method' => 'post', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin tạm ngừng theo dõi cán bộ</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Họ và tên cán bộ</label>
                    {!! Form::select('macanbo', $a_canbo, null, ['id' => 'macanbo', 'class' => 'form-control']) !!}

                    <label class="form-control-label">Phân loại</label>
                    {!! Form::select('maphanloai', getPhanLoaiTamNgungTheoDoi(), null, [
                        'id' => 'maphanloai',
                        'class' => 'form-control',
                    ]) !!}

                    <label class="form-control-label">Từ ngày</label>
                    <input type="date" name="ngaytu" id="ngaytu" class="form-control" />

                    <label class="form-control-label">Đến ngày</label>
                    <input type="date" name="ngayden" id="ngayden" class="form-control" />

                    <label class="form-control-label">Nội dung chi tiết</label>
                    {!! Form::textarea('noidung', null, ['id' => 'noidung', 'class' => 'form-control', 'rows' => '3']) !!}

                    <input type="hidden" id="maso" name="maso" />
                    <input type="hidden" id="madv" name="madv" value="{{ session('admin')->madv }}" />
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

    <script>
        function add() {
            $('#maso').val('');
            $('#create-modal').modal('show');
        }

        function editCV(maso) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ $furl }}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    maso: maso
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#macanbo').val(data.macanbo);
                    $('#maphanloai').val(data.maphanloai);
                    $('#ngaytu').val(data.ngaytu);
                    $('#ngayden').val(data.ngayden);
                    $('#noidung').val(data.noidung);
                    $('#maso').val(data.maso);
                },
                error: function(message) {
                    toastr.error(message, 'Lỗi!');
                }
            });
            $('#create-modal').modal('show');
        }

        $(function() {
            $('#create :submit').click(function() {
                var ok = true,
                    str_message = '';

                if ($('#ngaytu').val() == '' || $('#ngayden').val() == '') {
                    ok = false;
                    str_message += 'Thời gian tạm ngừng theo dõi không được bỏ trống \n'
                }

                if (ok == false) {
                    alert(str_message);
                    $("form").submit(function(e) {
                        e.preventDefault();
                    });
                } else {
                    $("form").unbind('submit').submit();
                }
            });
        });
    </script>

    @include('includes.modal.delete')
@stop
