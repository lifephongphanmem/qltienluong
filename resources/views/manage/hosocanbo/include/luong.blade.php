<!--form4 thông tin luong va phu cap -->
<div id="tab4" class="tab-pane" >

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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Mã ngạch <span class="require">*</span></label>
                                {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','autofocus'=>'autofocus','readonly'=>'true'))!!}
                            </div>
                        </div>

                        @if(isset($model))
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Ngạch bậc </label>
                                    <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                        <option value="">--Chọn mã ngạch lương--</option>
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Bậc lương </label>
                                    <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                        @for($i=1;$i<=16;$i++)
                                            <option value="{{$i}}" {{$i == $model->bac?'selected':''}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Ngạch bậc </label>
                                    <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                        <option value="">--Chọn mã ngạch lương--</option>
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Bậc lương </label>
                                    <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                        @for($i=1;$i<=16;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Từ ngày</label>
                                {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Đến ngày</label>
                                {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Hệ số lương </label>
                                {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Phần trăm v.khung </label>
                                <div class="input-group bootstrap-touchspin">
                                    {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                </div>

                            </div>
                        </div>

                        <!--div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Tỷ lệ hưởng lương</label>
                                <div class="input-group bootstrap-touchspin">
                                    {!!Form::text('pthuong', '100', array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                </div>
                            </div>
                        </div-->

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Phụ cấp (hưu trí)</label>
                                {!!Form::text('hesopc', null, array('id' => 'hesopc','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                        (for theo mang)
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                <div class="portlet-body" style="display: block;">

                    <div class="form-body">
                        <div class="row">
                            @foreach($a_phucap as $key=>$val)
                                @if($a_donvi[$key] == 2)
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">{{$val}}</label>
                                            <div class="input-group bootstrap-touchspin">
                                                {!!Form::text($key, null, array('id' =>$key, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($a_donvi[$key] == 1)
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">{{$val}}</label>
                                            <div class="input-group bootstrap-touchspin">
                                                {!!Form::text($key, null, array('id' =>$key, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix">Đ</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">{{$val}}</label>
                                            <div class="input-group bootstrap-touchspin">
                                                {!!Form::text($key, null, array('id' =>$key, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix">0.0</span>
                                            </div>
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
                        Thông tin truy lĩnh lương
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                <div class="portlet-body" style="display: block;">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Truy lĩnh từ ngày</label>
                                {!! Form::input('date','truylinhtungay',null,array('id' => 'truylinhtungay', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Truy lĩnh đến ngày</label>
                                {!! Form::input('date','truylinhdenngay',null,array('id' => 'truylinhdenngay', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Hệ số lương truy lĩnh</label>
                                {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>


                    </div>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
</div>
@include('includes.script.func_msnb')
<!--end form4 -->