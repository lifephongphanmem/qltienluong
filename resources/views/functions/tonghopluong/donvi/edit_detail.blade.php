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
                                    <label class="control-label">Hệ số lương truy thu</label>
                                    {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        </div>
                    </div>

                        <div style="text-align: center; border-top: 1px solid #eee;">

                                <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>

                            <a href="{{url('chuc_nang/nang_luong/maso=')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop