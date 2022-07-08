@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <?php
        $a_congthuc = explode(',',$model->congthuc);
    ?>
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
        $('#phanloai').val('{{$model->phanloai}}').trigger('change');
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin phụ cấp<small> chỉnh sửa</small>
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
                    {!! Form::model($model,['url'=>$furl.'update', 'id' => 'create_tttaikhoan','method' => 'post', 'class'=>'horizontal-form']) !!}

                    <input type="hidden" id="congthuc" name="congthuc" value="{{$model->congthuc}}"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Mã phụ cấp<span class="require">*</span></label>
                                    {!!Form::text('mapc', null, array('id' => 'mapc','class' => 'form-control','readonly'=>'true','autofocus'=>'true'))!!}
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
                                    <label class="form-control-label">Gồm các loại hệ số</label>
                                    {!!Form::select('ctpc', getCongThucTinhPC(), null, array('id' => 'ctpc','class' => 'form-control select2me','multiple'=>'multiple'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tiêu đề trên Form<span class="require">*</span></label>
                                    {!!Form::text('form', null, array('id' => 'form','class' => 'form-control','required'=>'required'))!!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tiêu đề trên báo cáo<span class="require">*</span></label>
                                    {!!Form::text('report', null, array('id' => 'report','class' => 'form-control','required'=>'required'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tổng hợp số liệu</label>
                                    {!!Form::select('tonghop',array('0'=>'Không','1'=>'Có'), null, array('id' => 'tonghop','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Dự toán lương</label>
                                    {!!Form::select('dutoan',array('0'=>'Không','1'=>'Có'), null, array('id' => 'dutoan','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Nộp bảo hiểm</label>
                                    {!!Form::select('baohiem',array('0'=>'Không','1'=>'Có'), null, array('id' => 'baohiem','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Trừ nghỉ phép, nghỉ ốm</label>
                                    {!!Form::select('nghiom',array('0'=>'Không','1'=>'Có'), null, array('id' => 'nghiom','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Chế độ thai sản</label>
                                    {!!Form::select('thaisan',array('0'=>'Không','1'=>'Có'), null, array('id' => 'thaisan','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Chế độ điều động</label>
                                    {!!Form::select('dieudong',array('0'=>'Không','1'=>'Có'), null, array('id' => 'dieudong','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tính thuế thu nhập cá nhân</label>
                                    {!!Form::select('thuetn',array('0'=>'Không','1'=>'Có'), null, array('id' => 'thuetn','class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Tính tập sự, thử việc</label>
                                    {!!Form::select('tapsu',array('0'=>'Không','1'=>'Có'), null, array('id' => 'tapsu','class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="text-align: center">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                    <a href="{{url($furl.'index')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
                {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        $("#phanloai").change(function(){
            var congthuc = '{{$model->congthuc}}';
            if(congthuc == ''){
                congthuc = 'heso,vuotkhung,pccv';
            }
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