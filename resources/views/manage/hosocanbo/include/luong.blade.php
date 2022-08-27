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
                        <input type="hidden" name="namnangluong" id="namnangluong" value="{{isset($namnb)?$namnb : 0}}">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Mã ngạch <span class="require">*</span></label>
                                {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','autofocus'=>'autofocus','readonly'=>'true'))!!}
                            </div>
                        </div>

                        @if(isset($model))
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-md-11">
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
                                    <div class="col-md-1" style="padding-left: 0px;">
                                        <label class="control-label">&nbsp;&nbsp;&nbsp;</label>
                                        <button type="button" class="btn btn-default" data-target="#modal-vitri" data-toggle="modal"><i class="fa glyphicon glyphicon-list-alt"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div id="div_bac" class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Bậc lương</label>
                                    <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                        @for($i=1;$i<=16;$i++)
                                            <option value="{{$i}}" {{$i == $model->bac?'selected':''}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-md-11">
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

                                    <div class="col-md-1" style="padding-left: 0px;">
                                        <label class="control-label">&nbsp;&nbsp;&nbsp;</label>
                                        <button type="button" class="btn btn-default" data-target="#modal-vitri" data-toggle="modal"><i class="fa glyphicon glyphicon-list-alt"></i></button>
                                    </div>
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
                                <label class="control-label">Hệ số lương </label>
                                {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Thâm niên vượt khung </label>
                                <div class="input-group bootstrap-touchspin">
                                    {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Ngạch bậc - Từ ngày</label>
                                {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control ngachbac'))!!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Ngạch bậc - Đến ngày</label>
                                {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control ngachbac'))!!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tỷ lệ hưởng lương</label>
                                <div class="input-group bootstrap-touchspin">
                                    {!!Form::text('pthuong', null, array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal','title'=>"Áp dụng trong trường hợp: Cán bộ đi học, đi công tác; Cán bộ thử việc, tập sự"))!!}
                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                </div>
                            </div>
                        </div>

                        <?php $hesopc = $model_pc->wherein('mapc',['luonghd']); ?>
                        @foreach($hesopc as $pc)
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

                                @if($pc->mapc == 'pctnn' && $pc->phanloai != 3)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">TN nghề - Từ ngày</label>
                                            {!! Form::input('date','tnntungay',null,array('id' => 'tnntungay', 'class' => 'form-control thamnien'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">TN nghề - Đến ngày</label>
                                            {!! Form::input('date','tnndenngay',null,array('id' => 'tnndenngay', 'class' => 'form-control thamnien'))!!}
                                        </div>
                                    </div>
                                @elseif($pc->mapc == 'pctnn' && $pc->phanloai == 3)
                                    {!!Form::hidden('tnntungay', null, array('id' =>'tnntungay', 'class' => 'form-control'))!!}
                                    {!!Form::hidden('tnndenngay', null, array('id' =>'tnndenngay', 'class' => 'form-control'))!!}
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

<script>
    $(function(){
        $("#ngaytu").change(function(){
            $("#ngayden").val(add_date($('#ngaytu').val(),$('#namnangluong').val()));
        });
        $("#tnntungay").change(function(){
            $("#tnndenngay").val(add_date($('#tnntungay').val(),1));
        });
    });

    function add_date(thoidiem,sonam){
        var date = new Date();
        if(thoidiem != '' && sonam != ''){
            var date = new Date(thoidiem);
            var dd = date.getDate();
            var mm = date.getMonth() + 1;
            var y = date.getFullYear() + parseInt(sonam);
            if(dd<10) {
                dd='0'+dd;
            }
            if(mm<10) {
                mm='0'+mm;
            }
            return (y + '-' + mm + '-' + dd);
        }
        return '';
    }
</script>
@include('includes.script.func_msnb')
@include('includes.modal.mangach')
<!--end form4 -->