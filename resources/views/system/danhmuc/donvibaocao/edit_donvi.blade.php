@extends('main')

@section('custom-style')
    <link href="{{url('assets/global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
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
        Thông tin đơn vị<small> chỉnh sửa</small>
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
                    {!! Form::model($model,['url'=>$url.'update_donvi', 'class'=>'horizontal-form','id'=>'update_tthethong','method' => 'PATCH']) !!}
                    <input type="hidden" name="madv" id="madv" value="{{$model->madv}}" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Mã quan đơn vị<span class="require">*</span></label>
                                        {!!Form::text('madv', null, array('id' => 'madv','class' => 'form-control','readonly'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tên đơn vị<span class="require">*</span></label>
                                        {!!Form::text('tendv', null, array('id' => 'tendv','class' => 'form-control required'))!!}
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
                                        <label class="control-label">Số điện thoại</label>
                                        {!!Form::text('sodt', null, array('id' => 'sodt','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Chức danh thủ trưởng</label>
                                        {!!Form::text('cdlanhdao', null, array('id' => 'cdlanhdao','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Thủ trưởng đơn vị</label>
                                        {!!Form::text('lanhdao', null, array('id' => 'lanhdao','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Người lập biểu</label>
                                        {!!Form::text('nguoilapbieu', null, array('id' => 'nguoilapbieu','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Đơn vị chủ quản</label>
                                        {!!Form::select('macqcq', $model_donvi, null, array('id' => 'macqcq','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Phân loại đơn vị</label>
                                        {!!Form::select('maphanloai', $model_phanloai, null, array('id' => 'maphanloai','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Cấp dự toán</label>
                                        {!!Form::select('capdonvi', $model_capdv, null, array('id' => 'capdonvi','class' => 'form-control'))!!}
                                    </div>
                                </div>
                                @if($model->maphanloai == 'KVXP')
                                    <div id="plxa" class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Phân loại xã phường</label>
                                            {!!Form::select('phanloaixa', $model_plxa, null, array('id' => 'phanloaixa','class' => 'form-control','required'=>'required'))!!}
                                        </div>
                                    </div>
                                @else
                                    <div id="plxa" class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Lĩnh vực hoạt động</label>
                                            {!!Form::select('linhvuchoatdong', $model_linhvuc, null, array('id' => 'linhvuchoatdong','class' => 'form-control','required'=>'required', 'multiple'=>'multiple'))!!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    <!-- END FORM-->
                </div>
            </div>
            <div class="row" style="text-align: center">
                <div class="col-md-12">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Cập nhật</button>
                    <a href="{{url('/danh_muc/khu_vuc/ma_so='.$model->madvbc.'/list_unit')}}" class="btn default">Hủy</a>
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
            $("#linhvuchoatdong").select2();
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