@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            $('#heso').prop('readonly',true);
            $('#vuotkhung').prop('readonly',true);
            $('#pccv').prop('readonly',true);
        });
    </script>
@stop

@section('content')
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row center">
        <div class="col-md-12 center">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        Thông tin lương và các khoản phụ cấp của cán bộ
                    </div>
                </div>

                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::model($model,['url'=>$furl.'store', 'id' => 'frm_ThemCanBo', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="tencanbo" name="tencanbo" value="{{$model->tencanbo}}"/>
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <input type="hidden" id="maphanloai" name="maphanloai" value="{{$model->maphanloai}}"/>
                    <input type="hidden" id="msngbac" name="msngbac" value="{{$model->msngbac}}"/>
                    <input type="hidden" id="mact" name="mact" value="{{$model->mact}}"/>
                    <input type="hidden" id="macvcq" name="macvcq" value="{{$model->macvcq}}"/>
                    <input type="hidden" id="mapb" name="mapb" value="{{$model->mapb}}"/>
                    <input type="hidden" id="stt" name="stt" value="{{$model->stt}}"/>

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
                                                    <div class="col-md-11" style="padding-left: 0px;">
                                                        <label class="form-control-label">Họ và tên</label>
                                                        {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control'))!!}
                                                    </div>
                                                    <div class="col-md-1" style="padding-left: 0px;">
                                                        <label class="form-control-label text-center">&nbsp;&nbsp;&nbsp;</label>
                                                        <button type="button" class="btn btn-default" data-target="#modal-canbo" data-toggle="modal"><i class="fa glyphicon glyphicon-list-alt"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Khối/Tổ công tác</label>
                                                    {!!Form::text('tentruylinh', null, array('id' => 'tentruylinh','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Chức vụ/Chức danh</label>
                                                    {!!Form::text('macvcq', null, array('id' => 'tentruylinh','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại công tác</label>
                                                    <div class="input-group bootstrap-touchspin">
                                                        {!!Form::text('pthuong', null, array('id' =>'pthuong', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">STT</label>
                                                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Phần trăm hưởng lương</label>
                                                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Số ngày công</label>
                                                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Số làm việc</label>
                                                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Đến ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số tháng truy lĩnh </label>
                                                    {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số ngày truy lĩnh </label>
                                                    {!!Form::text('ngaytl', null, array('id' => 'ngaytl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Nội dung truy lĩnh </label>
                                                    {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
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
                                            Thông tin các khoản lương và phụ cấp
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                @foreach($model_pc as $pc)
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

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin bảo hiểm
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Phân loại truy lĩnh</label>
                                                    {!!Form::text('tentruylinh', null, array('id' => 'tentruylinh','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Phần trăm hưởng</label>
                                                    <div class="input-group bootstrap-touchspin">
                                                        {!!Form::text('pthuong', null, array('id' =>'pthuong', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Đến ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số tháng truy lĩnh </label>
                                                    {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số ngày truy lĩnh </label>
                                                    {!!Form::text('ngaytl', null, array('id' => 'ngaytl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Nội dung truy lĩnh </label>
                                                    {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                                                </div>
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
            <div style="text-align: center; padding-bottom: 15px">
                <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                <a href="{{url('nghiep_vu/truy_linh/danh_sach?thang=ALL&nam='.date('Y'))}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
            </div>
            {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        function validateForm(){
            var validator = $("#frm_ThemCanBo").validate({
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