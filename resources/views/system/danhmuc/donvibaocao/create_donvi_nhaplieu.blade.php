@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop


@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>

@stop

@section('content')


    <h3 class="page-title">
        Thông tin đơn vị thêm mới <small>đơn vị nhập liệu</small>
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN DASHBOARD STATS -->
    <div class="row center">
        <div class="col-md-12 center">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box blue">
                <!--div class="portlet-title">
                    </div-->
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['url' => $url . 'store_donvi', 'id' => 'create_tttaikhoan', 'class' => 'horizontal-form']) !!}
                    <input type="hidden" name="madvbc" id="madvbc" value="{{ $inputs['ma_so'] }}" />
                    <input type="hidden" name="phanloaitaikhoan" id="phanloaitaikhoan"
                        value="{{ $inputs['phan_loai'] }}" />

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tên đơn vị<span class="require">*</span></label>
                                    {!! Form::text('tendv', null, ['id' => 'tendv', 'class' => 'form-control required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Địa chỉ</label>
                                    {!! Form::text('diachi', null, ['id' => 'diachi', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Địa danh</label>
                                    {!! Form::text('diadanh', null, ['id' => 'diadanh', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Đơn vị gửi dữ liệu tổng hợp</label>
                                    {!! Form::select('macqcq', $model_donvi, null, ['id' => 'macqcq', 'class' => 'form-control required']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phân loại đơn vị</label>
                                    {!! Form::select('maphanloai', $model_phanloai, 'KVXP', ['id' => 'maphanloai', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Cấp dự toán</label>
                                    {!! Form::select('capdonvi', $model_capdv, null, ['id' => 'capdonvi', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div id="plxa" class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phân loại xã phường</label>
                                    {!! Form::select('phanloaixa', $model_plxa, null, ['id' => 'phanloaixa', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tài khoản đăng nhập</label>
                                    {!! Form::text('username', null, ['id' => 'username', 'class' => 'form-control required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Mật khẩu</label>
                                    {!! Form::text('password', null, ['id' => 'password', 'class' => 'form-control required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Thêm
                    mới</button>
                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i>&nbsp;Nhập lại</button>
                <a href="{{ url('/danh_muc/khu_vuc/chi_tiet?ma_so=' . $inputs['ma_so'] . '&phan_loai=' . $inputs['phan_loai']) }}"
                    class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
            </div>
            {!! Form::close() !!}
            <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        function validateForm() {

            var validator = $("#create_tttaikhoan").validate({
                rules: {
                    name: "required",
                    tendv: "required"

                },
                messages: {
                    name: "Chưa nhập dữ liệu",
                    tendv: "Chưa nhập dữ liệu"
                }
            });
        }
    </script>
    <script>
        jQuery(document).ready(function($) {
            $('input[name="madv"]').change(function() {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var madv = $(this).val();
                if (madv != '') {
                    $.ajax({
                        type: 'GET',
                        url: '/ajax/checkmadv',
                        data: {
                            _token: CSRF_TOKEN,
                            madv: madv
                        },
                        success: function(respond) {
                            if (respond == 'false') {
                                toastr.error("Mã đơn vị đã tồn tại.", "Lỗi!");
                                $('input[name="madv"]').val('');
                                $('input[name="madv"]').focus();
                            }
                        }
                    });
                }
            })
            $('#maphanloai').change(function() {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/danh_muc/khu_vuc/getPhanLoai',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        maphanloai: this.value
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        $('#plxa').replaceWith(data.message);
                        $("#linhvuchoatdong").select2();
                    },
                    error: function(message) {
                        toastr.error(message, 'Lỗi!');
                    }
                });

            });
        }(jQuery));
    </script>
@stop
