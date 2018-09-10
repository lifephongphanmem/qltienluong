@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script>
        $(function() {
            //Multi select box
            //$("#ctpc").select2();
            $("#ctpc").change(function () {
                $("#congthuc").val($("#ctpc").val());
            });
        });
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin cán bộ truy lĩnh lương
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
                    {!! Form::model($model,['url'=>$furl.'store', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin chung
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label">Họ tên cán bộ<span class="require">*</span></label>
                                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control','required','readonly'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control','required'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label">Đến ngày<span class="require">*</span></label>
                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control','required'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Hệ số truy lĩnh </label>
                                    {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nội dung truy lĩnh </label>
                                    {!!Form::text('noidung', null, array('id' => 'noidung','class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin các khoản phụ cấp
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                @foreach($model_pc as $pc)
                                                    @if(in_array($pc->mapc,$a_heso))
                                                        @continue
                                                    @endif

                                                    @if($pc->phanloai == 3)
                                                        {!!Form::hidden($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    @elseif($pc->phanloai == 2)
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                <div class="input-group bootstrap-touchspin">
                                                                    {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($pc->phanloai == 1)
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                <div class="input-group bootstrap-touchspin">
                                                                    {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                                    <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="text-align: center">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                    <a href="{{url($furl.'danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
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
                    tendv :"required"

                },
                messages: {
                    name :"Chưa nhập dữ liệu",
                    tendv :"Chưa nhập dữ liệu"
                }
            });
        }
    </script>

@stop