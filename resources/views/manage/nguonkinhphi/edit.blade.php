@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
    <script>
        $('#tennb').val('{{$model->msngbac}}').trigger('change');
        $('#bac').val('{{$model->bac}}').trigger('change');
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        CHI TIẾT NGUỒN KINH PHÍ
                    </div>
                </div>
                {!! Form::model($model, ['url'=>$furl.'update', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                <input type="hidden" id="masodv" name="masodv" value="{{$model->masodv}}" />

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
                                            <label class="control-label">Năm ngân sách</label>
                                            {!!Form::text('namns', null, array('id' => 'namns','class' => 'form-control', 'readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Căn cứ thông tư, quyết định</label>
                                        {!!Form::select('sohieu',getThongTuQD(false), null, array('id' => 'sohieu','class' => 'form-control','disabled'=>'true'))!!}
                                    </div>

                                    <!-- Ẩn / hiện element in form (không mất trường trên form)-->
                                    <div class="col-md-3" {{session('admin')->maphanloai != 'KVXP'?'':'style=display:none'}}>
                                        <div class="form-group" >
                                            <label class="control-label">Lĩnh vực hoạt động </label>
                                            {!!Form::select('linhvuchoatdong',getLinhVucHoatDong() , null, array('id' => 'linhvuchoatdong','class' => 'form-control', 'disabled'=>'true'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="control-label">Nội dung</label>
                                        {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                                    </div>
                                </div>
                            </div>
                        <!-- END PORTLET-->
                    </div>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    Thông tin nhu cầu kinh phí
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Lương, phụ cấp</label>
                                                {!!Form::text('luongphucap', null, array('id' => 'luongphucap','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Phí hoạt động ĐBHĐND</label>
                                                {!!Form::text('daibieuhdnd', null, array('id' => 'daibieuhdnd','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Trợ cấp bộ đã nghỉ hưu</label>
                                                {!!Form::text('nghihuu', null, array('id' => 'nghihuu','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Cán bộ không chuyên trách</label>
                                                {!!Form::text('canbokct', null, array('id' => 'canbokct','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Phụ cấp trách nhiệm cấp ủy</label>
                                                {!!Form::text('uyvien', null, array('id' => 'uyvien','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class=" control-label">Bồi dưỡng hoạt động cấp ủy</label>
                                                {!!Form::text('boiduong', null, array('id' => 'boiduong','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Tổng số</label>
                                                {!!Form::text('nhucau', null, array('id' => 'nhucau','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                            </div>
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
                                    Thông tin nguồn kinh phí
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Tiết kiệm chi 10%</label>
                                            {!!Form::text('tietkiem', null, array('id' => 'tietkiem','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Học phí</label>
                                            {!!Form::text('hocphi', null, array('id' => 'hocphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Viện phí</label>
                                            {!!Form::text('vienphi', null, array('id' => 'vienphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn thu khác</label>
                                            {!!Form::text('nguonthu', null, array('id' => 'nguonthu','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tổng số</label>
                                            {!!Form::text('nguonkp', null, array('id' => 'nguonkp','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div>

                <div  class="form-actions" style="text-align: center; border-top: 1px solid #eee;">
                    <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                    <a href="{{url($furl.'detail/ma_so='.$model->mathdv)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>

        $('.kinhphi').change(function(){
            var tong = 0;
            $('.kinhphi').each(function () {
                tong += getdl($(this).val());
            });
            $('#nguonkp').val(tong);
        })

        $('.nhucaukp').change(function(){
            var tong = 0;
            $('.nhucaukp').each(function () {
                tong += getdl($(this).val());
            });
            $('#nhucau').val(tong);
        })


    </script>

@stop