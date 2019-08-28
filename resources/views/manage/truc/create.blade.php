@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>

    @include('includes.script.scripts')
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            //TableManaged.init();
        });
    </script>
@stop

@section('content')
    <h3 class="page-title">
        Thông tin trực của cán bộ - {{$model->tencanbo}}
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
                    {!! Form::model($model,['url'=>$inputs['furl'].'store', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="trangthai" name="trangthai" value="{{$inputs['trangthai']}}"/>
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="tencanbo" name="tencanbo" value="{{$model->tencanbo}}"/>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}"/>
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
                                                    <label class="control-label">Tháng</label>
                                                    {!!Form::text('thang', null, array('id' => 'thang','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Năm</label>
                                                    {!!Form::text('nam', null, array('id' => 'nam','class' => 'form-control', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số ngày công <span class="require">*</span></label>
                                                    {!!Form::text('songaycong', null, array('id' => 'songaycong','class' => 'form-control required', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số ngày trực <span class="require">*</span></label>
                                                    {!!Form::text('songaytruc', null, array('id' => 'songaytruc','class' => 'form-control required', 'data-mask'=>'fdecimal'))!!}
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
                                                        @if($pc->phanloai == 3)
                                                            {!!Form::hidden($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                                        @elseif($pc->phanloai == 2)
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{$pc->form}}</label>
                                                                    <div class="input-group bootstrap-touchspin">
                                                                        {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($pc->phanloai == 1)
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{$pc->form}}</label>
                                                                    <div class="input-group bootstrap-touchspin">
                                                                        {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{$pc->form}}</label>
                                                                    {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
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
                    <div style="text-align: center; padding-bottom: 15px">
                        @if(!isset($model->mabl) ||(isset($model->mabl) && $model->mabl == null))
                            <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                        @endif
                        <a href="{{url('nghiep_vu/truc/danh_sach?thang='.$model->thang.'&nam='.$model->nam)}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                    {!! Form::close() !!}
                    <!-- END FORM-->
                <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.ngaythang').change(function(){
            tinhtoan();
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