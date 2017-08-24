@extends('main')

@section('custom-style')

@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <!--cript src="{{url('assets/admin/pages/scripts/form-validation.js')}}"></script-->
    <script>
        function InputMask(){
            //$(function(){
            // Input Mask
            if($.isFunction($.fn.inputmask))
            {
                $("[data-mask]").each(function(i, el)
                {
                    var $this = $(el),
                            mask = $this.data('mask').toString(),
                            opts = {
                                numericInput: attrDefault($this, 'numeric', false),
                                radixPoint: attrDefault($this, 'radixPoint', ''),
                                rightAlignNumerics: attrDefault($this, 'numericAlign', 'left') == 'right'
                            },
                            placeholder = attrDefault($this, 'placeholder', ''),
                            is_regex = attrDefault($this, 'isRegex', '');


                    if(placeholder.length)
                    {
                        opts[placeholder] = placeholder;
                    }

                    switch(mask.toLowerCase())
                    {
                        case "phone":
                            mask = "(999) 999-9999";
                            break;

                        case "currency":
                        case "rcurrency":

                            var sign = attrDefault($this, 'sign', '$');;

                            mask = "999,999,999.99";

                            if($this.data('mask').toLowerCase() == 'rcurrency')
                            {
                                mask += ' ' + sign;
                            }
                            else
                            {
                                mask = sign + ' ' + mask;
                            }

                            opts.numericInput = true;
                            opts.rightAlignNumerics = false;
                            opts.radixPoint = '.';
                            break;

                        case "email":
                            mask = 'Regex';
                            opts.regex = "[a-zA-Z0-9._%-]+@[a-zA-Z0-9-]+\\.[a-zA-Z]{2,4}";
                            break;

                        case "fdecimal":
                            mask = 'decimal';
                            $.extend(opts, {
                                autoGroup		: true,
                                groupSize		: 3,
                                radixPoint		: attrDefault($this, 'rad', '.'),
                                groupSeparator	: attrDefault($this, 'dec', ',')
                            });
                    }

                    if(is_regex)
                    {
                        opts.regex = mask;
                        mask = 'Regex';
                    }

                    $this.inputmask(mask, opts);
                });
            }
            //});
        }

        // </editor-fold>
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin hệ thống<small> chỉnh sửa</small>
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
                    {!! Form::model($model,['url'=>$url. $model->id.'/global', 'class'=>'horizontal-form','id'=>'update_tthethong']) !!}
                            <meta name="csrf-token" content="{{ csrf_token() }}" />
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tuổi nghỉ hưu nam</label>
                                        {!!Form::text('tuoinam', null, array('id' => 'tuoinam','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Thời gian hết tập sự (tháng)</label>
                                        {!!Form::text('tg_hetts', null, array('id' => 'tg_hetts','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tuổi nghỉ hưu nữ</label>
                                        {!!Form::text('tuoinu', null, array('id' => 'tuoinu','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Thời gian xét nâng lương (tháng)</label>
                                        {!!Form::text('tg_xetnl', null, array('id' => 'tg_xetnl','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHXH người lao động đóng (%)</label>
                                        {!!Form::text('bhxh', null, array('id' => 'bhxh','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHXH đơn vị đóng (%)</label>
                                        {!!Form::text('bhxh_dv', null, array('id' => 'bhxh_dv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHYT người lao động đóng (%)</label>
                                        {!!Form::text('bhyt', null, array('id' => 'bhyt','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHYT đơn vị đóng (%)</label>
                                        {!!Form::text('bhyt_dv', null, array('id' => 'bhyt_dv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHTN người lao động đóng (%)</label>
                                        {!!Form::text('bhtn', null, array('id' => 'bhtn','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">BHTN đơn vị đóng (%)</label>
                                        {!!Form::text('bhtn_dv', null, array('id' => 'bhtn_dv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">KPCĐ người lao động đóng (%)</label>
                                        {!!Form::text('kpcd', null, array('id' => 'kpcd','class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">KPCĐ đơn vị đóng (%)</label>
                                        {!!Form::text('kpcd_dv', null, array('id' => 'kpcd_dv','class' => 'form-control'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Lương cơ bản</label>
                                        {!!Form::text('luongcb', null, array('id' => 'luongcb','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

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