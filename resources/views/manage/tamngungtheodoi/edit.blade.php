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
        Thông tin cán bộ tạm ngưng theo dõi<small> chỉnh sửa</small>
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
                    {!! Form::model($model,['url'=>$furl.'update', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Họ tên cán bộ<span class="require">*</span></label>
                                    {!!Form::select('macanbo',$a_canbo, null, array('id' => 'macanbo','class' => 'form-control','required','autofocus'=>'true'))!!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Phân loại</label>
                                    {!!Form::select('maphanloai',$a_phanloai, null, array('id' => 'maphanloai','class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control','required'))!!}
                                </div>
                            </div>

                            @if(!in_array($model->maphanloai, array('THAISAN','KHONGLUONG','DAINGAY')))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Số ngày nghỉ</label>
                                        {!!Form::text('songaynghi', null, array('id' =>'songaynghi', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>
                                <input type="hidden" id="ngayden" name="ngayden"/>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Đến ngày</label>
                                        {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control','required'))!!}
                                    </div>
                                </div>
                                <input type="hidden" id="songaynghi" name="songaynghi" value="0" />
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Nội dung tạm ngưng theo dõi</label>
                                    {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                                </div>
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