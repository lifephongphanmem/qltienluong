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
        Thông tin phụ cấp<small> thêm mới</small>
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
                    {!! Form::open(['url'=>$furl.'store', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="congthuc" name="congthuc" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Mã phụ cấp<span class="require">*</span></label>
                                    {!!Form::text('mapc', null, array('id' => 'mapc','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tên phụ cấp<span class="require">*</span></label>
                                    {!!Form::text('tenpc', null, array('id' => 'tenpc','class' => 'form-control','required'=>'required'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Phân loại</label>
                                    {!!Form::select('phanloai', getPhanLoaiPhuCap(), null, array('id' => 'phanloai','class' => 'form-control select2me'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Gồm các loại hệ số<span class="require">*</span></label>
                                    {!!Form::select('ctpc',getCongThucTinhPC(), null, array('id' => 'ctpc','class' => 'form-control select2me','multiple'=>'multiple'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tiêu đề trên Form<span class="require">*</span></label>
                                    {!!Form::text('form', null, array('id' => 'form','class' => 'form-control','required'=>'required'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tiêu đề trên báo cáo<span class="require">*</span></label>
                                    {!!Form::text('report', null, array('id' => 'report','class' => 'form-control','required'=>'required'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Nộp bảo hiểm</label>
                                    {!!Form::select('baohiem',array('0'=>'Không nộp hiểm','1'=>'Có nộp hiểm'), null, array('id' => 'baohiem','class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="text-align: center">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Thêm mới</button>
                    <a href="{{url($furl.'index')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
                {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        $("#phanloai").change(function(){
            var congthuc = 'heso,vuotkhung,pccv';
            var selectedValuesTest = congthuc.split(',');

            if($("#phanloai").val() == '2'){
                $('#ctpc').val(selectedValuesTest).trigger("change");
            }else{
                $('#ctpc').val('').trigger("change");
            }

        });
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