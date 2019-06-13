@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        THÔNG TIN CHI TIẾT CHI TRẢ CỦA CÁN BỘ {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect_chikhac', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
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
                                                        <label class="control-label" style="font-weight: bold">Hệ số</label>
                                                        {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control heso', 'data-mask'=>'fdecimal', 'style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Thành tiền</label>
                                                        {!!Form::text('ttl', null, array('id' => 'ttl','class' => 'form-control', 'data-mask'=>'fdecimal','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END PORTLET-->
                                </div>
                            </div>
                        </div>
                    <hr>
                        <div style="text-align: center;">
                            <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                            <a href="{{url('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb=')}}" class="btn btn-default"><i class="fa fa-reply mlx"></i> Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>
    <script>
        $('.heso').change(function() {
            //getLuong();
        })

        function getLuong(){
            //getBaoHiem();
            var luong = getdl($('#luongcoban').val());
            var heso = parseFloat($('#hesott').val());
            var songay = parseFloat($('#songay').val());
            var ttl = luong * heso * songay;
            $('#ttl').val(ttl);
        }

    </script>
    @include('includes.script.scripts')
@stop