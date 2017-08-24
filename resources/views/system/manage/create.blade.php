@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>

@stop

@section('content')


    <h3 class="page-title">
        Thông tin đơn vị<small> thêm mới</small>
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
                    {!! Form::open(['url'=>$url.'store', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Mã quan đơn vị<span class="require">*</span></label>
                                        {!!Form::text('madv', null, array('id' => 'madv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tên đơn vị<span class="require">*</span></label>
                                        {!!Form::text('tendv', null, array('id' => 'tendv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Địa chỉ</label>
                                        {!!Form::text('diachi', null, array('id' => 'diachi','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Địa danh</label>
                                        {!!Form::text('diadanh', null, array('id' => 'diadanh','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Khối phòng ban</label>
                                        {!! Form::select(
                                        'makhoipb',
                                        $model_kpb,null,
                                        array('id' => 'makhoipb', 'class' => 'form-control select2me','required'=>'required'))
                                        !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Loại đơn vị</label>
                                        <select class="form-control select2me required" id="level" name="level">
                                            <option value="H">Đơn vị chủ quản</option>
                                            <option value="X">Đơn vị cơ sở</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Đơn vị chủ quản</label>
                                        <select class="form-control select2me" id="mahuyen" name="mahuyen">
                                            @foreach($model as $dv)
                                                <option value="{{$dv->madv}}">{{$dv->tendv}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tài khoản đăng nhập</label>
                                        {!!Form::text('username', null, array('id' => 'username','class' => 'form-control required'))!!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Mật khẩu</label>
                                        {!!Form::text('password', null, array('id' => 'password','class' => 'form-control required'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
                <div style="text-align: center">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Thêm mới</button>
                    <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i>&nbsp;Nhập lại</button>
                    <a href="{{url('/he_thong/quan_tri/don_vi')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
                {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        function validateForm(){

            var validator = $("#create_tttaikhoan").validate({
                rules: {
                    name :"required",
                    madv :"required",
                    tendv :"required"

                },
                messages: {
                    name :"Chưa nhập dữ liệu",
                    madv :"Chưa nhập dữ liệu",
                    tendv :"Chưa nhập dữ liệu"
                }
            });
        }
    </script>
    <script>
        jQuery(document).ready(function($) {
            $('input[name="madv"]').change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'GET',
                    url: '/ajax/checkmadv',
                    data: {
                        _token: CSRF_TOKEN,
                        madv:$(this).val()
                    },
                    success: function (respond) {
                        if(respond == 'false'){
                            toastr.error("Mã đơn vị đã tồn tại.", "Lỗi!");
                            $('input[name="madv"]').val('');
                            $('input[name="madv"]').focus();
                        }
                    }

                });
            })
        }(jQuery));
    </script>
@stop