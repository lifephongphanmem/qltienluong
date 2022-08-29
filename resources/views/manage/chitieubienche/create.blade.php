@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    @include('includes.script.scripts')
    <script>
        $('#mact').val('{{$model->mact}}').trigger('change');
        $('#mact_tuyenthem').val('{{$model->mact_tuyenthem}}').trigger('change');
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
                        Thông tin chỉ tiêu biên chế
                    </div>
                </div>

                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::model($model,['url'=>$furl.'store', 'id' => 'frm_ThemMoi', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="id" name="id" value="{{$model->id}}" />
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Lĩnh vực công tác </label>
                                                    {!! Form::select('linhvuchoatdong', getLinhVucHoatDong(false), session('admin')->linhvuchoatdong ,array('id' => 'linhvuchoatdong','class' => 'form-control select2me')) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại công tác</label>
                                                    <select class="form-control select2me" name="mact" id="mact" required="required">
                                                        @foreach($model_nhomct as $kieuct)
                                                            <optgroup label="{{$kieuct->tencongtac}}">
                                                                <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                                @foreach($mode_ct as $ct)
                                                                    <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Năm chỉ tiêu</label>
                                                    {!!Form::text('nam', null, array('class' => 'form-control text-right', 'readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số lượng được giao</label>
                                                    {!!Form::text('soluongduocgiao', null, array('id' => 'soluongduocgiao','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số lượng công chức được giao</label>
                                                    {!!Form::text('soluongcongchuc', null, array('id' => 'soluongcongchuc','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số lượng viên chức được giao</label>
                                                    {!!Form::text('soluongvienchuc', null, array('id' => 'soluongvienchuc','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số lượng hiện có</label>
                                                    {!!Form::text('soluongbienche', null, array('id' => 'soluongbienche','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số lượng chưa tuyển</label>
                                                    {!!Form::text('soluongtuyenthem', null, array('id' => 'soluongtuyenthem','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
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
                                            Thông tin các khoản lương và phụ cấp của cán bộ chưa tuyển
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Phân loại công tác chưa tuyển</label>
                                                        <select class="form-control select2me" name="mact_tuyenthem" id="mact_tuyenthem">
                                                            @foreach($model_nhomct as $kieuct)
                                                                <optgroup label="{{$kieuct->tencongtac}}">
                                                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                                    @foreach($mode_ct as $ct)
                                                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

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
                    </div>
                </div>
            </div>
            <div style="text-align: center; padding-bottom: 15px">
                <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                <a href="{{url('/nghiep_vu/chi_tieu/danh_sach?namct='.$model->nam)}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
            </div>
            {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        function validateForm(){
            var validator = $("#frm_ThemMoi").validate({
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