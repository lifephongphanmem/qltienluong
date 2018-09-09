@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
    <script>
        //$('#tennb').val('{{$model->msngbac}}').trigger('change');
        //$('#bac').val('{{$model->bac}}').trigger('change');
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        THÔNG TIN CHI TIẾT NÂNG THÂM NIÊN NGHỀ CỦA CÁN BỘ {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! Form::model($model, ['url'=>$furl.'store_detail', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}" />
                    <input type="hidden" id="manl" name="manl" value="{{$model->manl}}" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Thâm niên nghề</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text('pctnn', null, array('id' =>'pctnn', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Từ ngày <span class="require">*</span></label>
                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Đến ngày <span class="require">*</span></label>
                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Truy lĩnh từ ngày </label>
                                    {!! Form::input('date','truylinhtungay',null,array('id' => 'truylinhtungay', 'class' => 'form-control'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Hệ số lương truy lĩnh</label>
                                    {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; border-top: 1px solid #eee;">
                        @if($model_nangluong->trangthai != 'Đã nâng lương')
                            <button style="margin-top: 10px" type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                        @endif
                        <a style="margin-top: 10px" href="{{url($furl.'maso='.$model->manl)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.script.func_msnb')
@stop