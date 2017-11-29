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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Từ ngày</label>
                                {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
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
                                {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Phần trăm hưởng </label>
                                {!!Form::text('pthuong', '100', array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Hệ số lương truy lĩnh</label>
                                {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Hệ số phụ cấp</label>
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
                        Thông tin các khoản phụ cấp (nhập phần trăm)
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                <div class="portlet-body" style="display: block;">

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Công vụ </label>
                                    {!!Form::text('pccovu', null, array('id' => 'pccovu','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">T.niên v.khung </label>
                                    {!!Form::text('pctnvk', null, array('id' => 'pctnvk','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class=" control-label">Thâm niên nghề </label>
                                    {!!Form::text('pctnn', null, array('id' => 'pctnn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                        Thông tin các khoản phụ cấp (nhập hệ số)
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                <div class="portlet-body" style="display: block;">

                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Chức vụ </label>
                                    {!!Form::text('pccv', null, array('id' => 'pccv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Thâm niên </label>
                                    {!!Form::text('pcthni', null, array('id' => 'pcthni','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Kiêm nhiệm </label>
                                    {!!Form::text('pckn', null, array('id' => 'pckn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Trách nhiệm </label>
                                    {!!Form::text('pctn', null, array('id' => 'pctn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Ưu đãi ngành</label>
                                    {!!Form::text('pcudn', null, array('id' => 'pcudn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Khu vực </label>
                                    {!!Form::text('pckv', null, array('id' => 'pckv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Thu hút </label>
                                    {!!Form::text('pcth', null, array('id' => 'pcth','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Đặc biệt </label>
                                    {!!Form::text('pcdbn', null, array('id' => 'pcdbn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Lưu động </label>
                                    {!!Form::text('pcld', null, array('id' => 'pcld','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Độc hại </label>
                                    {!!Form::text('pcdh', null, array('id' => 'pcdh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Đại biểu HĐND </label>
                                    {!!Form::text('pcdbqh', null, array('id' => 'pcdbqh','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Cấp ủy viên </label>
                                    {!!Form::text('pcvk', null, array('id' => 'pcvk','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">B.dưỡng HĐCU </label>
                                    {!!Form::text('pcbdhdcu', null, array('id' => 'pcbdhdcu','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Công tác Đảng </label>
                                    {!!Form::text('pcdang', null, array('id' => 'pcdang','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Phụ cấp khác </label>
                                    {!!Form::text('pck', null, array('id' => 'pck','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
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