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
                        THÔNG TIN CHI TIẾT LƯƠNG CỦA CÁN BỘ {{$model->tencanbo}}
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
                                    <label class="control-label">Mã ngạch <span class="require">*</span></label>
                                    {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','autofocus'=>'autofocus','readonly'=>'true'))!!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Ngạch bậc </label>
                                    <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                        @foreach($m_plnb as $plnb)
                                            <optgroup label="{{$plnb->tennhom}}">
                                            <?php $mode_ct=$m_pln->where('manhom',$plnb->manhom); ?>
                                                @foreach($mode_ct as $ct)
                                                    <option value="{{$ct->msngbac}}" {{$model->msngbac == $ct->msngbac?'selected':''}}>{{$ct->tenngachluong}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Bậc lương </label>
                                    <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                        @for($i=1;$i<=16;$i++)
                                            <option value="{{$i}}" {{$i == $model->bac?'selected':''}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Hệ số lương </label>
                                    {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phần trăm vượt khung </label>
                                    {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <!--div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phần trăm hưởng </label>
                                    {!!Form::text('pthuong', '100', array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div-->

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
                        <a style="margin-top: 10px" href="{{url('chuc_nang/nang_luong/maso='.$model->manl)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.script.func_msnb')
@stop