@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <!--cript src="{{url('assets/admin/pages/scripts/form-validation.js')}}"></script-->

@stop

@section('content')


    <h3 class="page-title">
        Cấu hình hệ thống<small> chỉnh sửa</small>
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
                    {!! Form::model($model,['url'=>$url. $model->madv, 'class'=>'horizontal-form','id'=>'update_tthethong','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Mã quan hệ ngân sách</label>
                                        {!!Form::text('maqhns', null, array('id' => 'maqhns','class' => 'form-control'))!!}
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Tên đơn vị<span class="require">*</span></label>
                                        {!!Form::text('tendv', null, array('id' => 'tendv','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Địa chỉ</label>
                                        {!!Form::text('diachi', null, array('id' => 'diachi','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Chức danh thủ trưởng</label>
                                        {!!Form::text('cdlanhdao', null, array('id' => 'cdlanhdao','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Thủ trưởng đơn vị</label>
                                        {!!Form::text('lanhdao', null, array('id' => 'lanhdao','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Chức danh kế toán</label>
                                        {!!Form::text('cdketoan', null, array('id' => 'cdketoan','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Kế toán đơn vị</label>
                                        {!!Form::text('ketoan', null, array('id' => 'ketoan','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Người lập biểu</label>
                                        {!!Form::text('nguoilapbieu', null, array('id' => 'nguoilapbieu','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Địa danh</label>
                                        {!!Form::text('diadanh', null, array('id' => 'diadanh','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Phân loại mức độ tự chủ tài chính</label>
                                        {!!Form::select('phanloainguon', getPhanLoaiNguon(), 'NULL', array('id' => 'phanloainguon','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Phân loại đơn vị</label>
                                        {!!Form::select('maphanloai', $a_phanloai, null, array('id' => 'maphanloai','class' => 'form-control'))!!}
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Cấp dự toán</label>
                                        {!!Form::select('capdonvi', $model_capdv, null, array('id' => 'capdonvi','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Đơn vị tổng hợp dữ liệu<span class="require">*</span></label>
                                        {!!Form::select('macqcq', $model_donvi, null, array('id' => 'macqcq','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Số ngày công trong tháng</label>
                                        {!!Form::text('songaycong', null, array('id' =>'songaycong', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                @if($model->maphanloai == 'KVXP')
                                    <div id="plxa" class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label">Phân loại xã phường</label>
                                            {!!Form::select('phanloaixa', $model_plxa, null, array('id' => 'phanloaixa','class' => 'form-control','required'=>'required'))!!}
                                        </div>
                                    </div>
                                @else
                                    <div id="plxa" class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label">Lĩnh vực hoạt động</label>
                                            <select class="form-control" id="linhvuc" name="linhvuc" multiple="multiple">
                                                @if(isset($a_linhvuc))
                                                    @foreach($model_linhvuc as $key=>$value)
                                                        <option value="{{$key}}" {{in_array($key,$a_linhvuc)?'selected':''}}>{{$value}}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($model_linhvuc as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                    <input type="hidden" id="linhvuchoatdong" name="linhvuchoatdong" value="{{isset($model)?$model->linhvuchoatdong:''}}"/>
                                @endif
                            </div>
                        </div>

                    <!-- END FORM-->
                </div>
            </div>
            <div class="row" style="text-align: center">
                <div class="col-md-12">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Cập nhật</button>
                    <button type="reset" class="btn default">Hủy</button>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('#maphanloai').change (function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/danh_muc/khu_vuc/getPhanLoai',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        maphanloai: this.value
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#plxa').replaceWith(data.message);
                        $("#linhvuchoatdong").select2();
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi!');
                    }
                });

            });

            $("#linhvuc").select2();
            $("#linhvuc").change(function(){
                $("#linhvuchoatdong").val( $("#linhvuc").val());
            });
        });

        function validateForm(){
            var validator = $("#update_tthethong").validate({
                rules: {
                    ten:"required"
                },
                messages: {
                    ten: "Chưa nhập dữ liệu"
                }
            });
        }
    </script>
@stop