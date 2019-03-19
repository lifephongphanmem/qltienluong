@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    @include('includes.script.scripts')
    <script>
        $('#sunghiep').val('{{$model->sunghiep}}').trigger('change');
        $('#madv_dd').val('{{$model->madv_dd}}').trigger('change');
        $('#mact').val('{{$model->mact}}').trigger('change');
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin hồ sơ của cán bộ - {{$model->tencanbo}}
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
                    {!! Form::model($model,['url'=>$furl.'store_accept', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="gioitinh" name="gioitinh" value="{{$model->gioitinh}}"/>
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin luân chuyển, điều động cán bộ
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Phân loại<span class="require">*</span></label>
                                                    {!!Form::select('maphanloai', $a_phanloai, null, array('id' => 'maphanloai','class' => 'form-control','readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Ngày luân chuyển, điều động<span class="require">*</span></label>
                                                    {!! Form::input('date','ngaylc',null,array('id' => 'ngaylc', 'class' => 'form-control','readonly'))!!}
                                                </div>
                                            </div>
                                            @if($model->maphanloai == 'DIEUDONG')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Từ ngày</label>
                                                        {!! Form::input('date','ngaylctu',null,array('id' => 'ngaylctu', 'class' => 'form-control'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Đến ngày</label>
                                                        {!! Form::input('date','ngaylcden',null,array('id' => 'ngaylcden', 'class' => 'form-control'))!!}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Luân chuyển từ ngày</label>
                                                        {!! Form::input('date','ngaylctu',null,array('id' => 'ngaylctu', 'class' => 'form-control'))!!}
                                                    </div>
                                                </div>
                                                <input type="hidden"  name="ngaylcden" id="ngaylcden"/>
                                            @endif

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Nội dung</label>
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
                                            Thông tin hồ sơ cán bộ
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
                                                    <label class="control-label">Chức vụ (chức danh)</label>
                                                    {!!Form::select('macvcq',getChucVuCQ(false), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Sự nghiệp cán bộ</label>
                                                    <select class="form-control select2me" name="sunghiep" id="sunghiep" required="required">
                                                        <option value="Công chức">Công chức</option>
                                                        <option value="Viên chức">Viên chức</option>
                                                        <option value="Khác">Khác</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại công tác</label>
                                                    <select class="form-control select2me" name="mact" id="mact" required="required">
                                                        @foreach($model_nhomct as $kieuct)
                                                            <optgroup label="{{$kieuct->tencongtac}}">
                                                                <?php
                                                                    $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                                                ?>
                                                                @foreach($mode_ct as $ct)
                                                                    <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
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
                                            Thông tin lương và các khoản phụ cấp
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
                @if($model->trangthai != 'DACHUYEN')
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                @endif
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