@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    @include('includes.script.scripts')
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
                    {!! Form::model($model,['url'=>$url. $model->madv, 'class'=>'horizontal-form','id'=>'update_tthethong','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Mã quan hệ ngân sách</label>
                                        {!!Form::text('maqhns', null, array('id' => 'maqhns','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="control-label">Tên đơn vị</label>
                                        {!!Form::text('tendv', null, array('id' => 'tendv','class' => 'form-control', 'readonly'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Chức danh lãnh đạo</label>
                                        {!!Form::text('cdlanhdao', null, array('id' => 'cdlanhdao','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Họ và tên lãnh đạo</label>
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
                                        <label class="control-label">Họ và tên kế toán</label>
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
                                        <label class="control-label">Địa chỉ</label>
                                        {!!Form::text('diachi', null, array('id' => 'diachi','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Địa danh</label>
                                        {!!Form::text('diadanh', null, array('id' => 'diadanh','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Số điện thoại <span class="require">*</span></label>
                                        {!!Form::text('sodt', null, array('id' => 'sodt','class' => 'form-control required'))!!}
                                    </div>
                                </div>
                            </div>
                            @if(session('admin')->phamvitonghop == 'KHOI')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Đơn vị tổng hợp dữ liệu<span class="require">*</span></label>
                                            {!!Form::select('macqcq', $model_donvi, null, array('id' => 'macqcq','class' => 'form-control'))!!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    <!-- END FORM-->
                    <div class="form-actions">
                        <div class="row" style="text-align: center">
                            <div class="col-md-12">
                                <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Cập nhật</button>
                                <a href="{{url('/he_thong/don_vi/don_vi')}}" class="btn btn-default">&nbsp;Quay lại&nbsp;<i class="fa fa-reply"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $("#linhvuchoatdong").select2();
            var selectedValuesTest = '{{$model->linhvuchoatdong}}'.split(',');
            $('#linhvuchoatdong').val(selectedValuesTest).trigger('change');
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