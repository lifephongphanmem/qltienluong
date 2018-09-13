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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Ngạch bậc </label>
                                    <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                        @foreach($m_plnb as $plnb)
                                            <optgroup label="{{$plnb->tennhom}}">
                                            <?php $mode_ct=$m_pln->where('manhom',$plnb->manhom); ?>
                                                @foreach($mode_ct as $ct)
                                                    <option value="{{$ct->msngbac}}">{{$ct->tenngachluong}}</option>
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
                                            <option value="{{$i}}">{{$i}}</option>
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
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phần trăm vượt khung </label>
                                    {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phần trăm hưởng </label>
                                    {!!Form::text('pthuong', '100', array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp chức vụ </label>
                                    {!!Form::text('pccv', null, array('id' => 'pccv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp kiêm nhiệm </label>
                                    {!!Form::text('pckn', null, array('id' => 'pckn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp khu vực </label>
                                    {!!Form::text('pckv', null, array('id' => 'pckv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp công vụ </label>
                                    {!!Form::text('pccovu', null, array('id' => 'pccovu','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp trách nhiệm </label>
                                    {!!Form::text('pctn', null, array('id' => 'pctn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class=" control-label">Phụ cấp thâm niên nghề </label>
                                    {!!Form::text('pctnn', null, array('id' => 'pctnn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp thâm niên VK </label>
                                    {!!Form::text('pcvk', null, array('id' => 'pcvk','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp đại biểu HĐND </label>
                                    {!!Form::text('pcdbqh', null, array('id' => 'pcdbqh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp thu hút </label>
                                    {!!Form::text('pcth', null, array('id' => 'pcth','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp ưu đãi </label>
                                    {!!Form::text('pcudn', null, array('id' => 'pcudn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp đặc biệt </label>
                                    {!!Form::text('pcdbn', null, array('id' => 'pcdbn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp lưu động </label>
                                    {!!Form::text('pcld', null, array('id' => 'pcld','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp độc hại </label>
                                    {!!Form::text('pcdh', null, array('id' => 'pcdh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp thâm niên vượt khung </label>
                                    {!!Form::text('pctnvk', null, array('id' => 'pctnvk','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp bồi dưỡng HĐCU </label>
                                    {!!Form::text('pcbdhdcu', null, array('id' => 'pcbdhdcu','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp thâm niên </label>
                                    {!!Form::text('pcthni', null, array('id' => 'pcthni','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp công tác Đảng </label>
                                    {!!Form::text('pcdang', null, array('id' => 'pcdang','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp khác </label>
                                    {!!Form::text('pck', null, array('id' => 'pck','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                            <a href="{{url('chuc_nang/nang_luong/maso='.$model->manl)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.script.func_msnb')
@stop