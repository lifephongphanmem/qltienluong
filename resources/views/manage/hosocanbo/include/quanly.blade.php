<!--form1 thông tin cơ bản -->
<div id="tab0" class="tab-pane active" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Mã số cán bộ </label>
                    @if($type=='create')
                        {!!Form::text('macanbo', $macanbo, array('id' => 'macanbo','class' => 'form-control','readonly'=>'true'))!!}
                    @else
                        {!!Form::text('macanbo', null, array('id' => 'macanbo','class' => 'form-control','readonly'=>'true'))!!}
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Mức độ bảo mật <span class="require">*</span> </label>
                    <select name="macvcq" id="macvcq" class="form-control" required="required">
                        @foreach($m_cvcq as $cv)
                            <option value="{{$cv->macvcq}}">{{$cv->tencv}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Đơn vị quản lý <span class="require">*</span></label>
                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control', 'required'=>'required'))!!}
                </div>
            </div>
        </div>

        <div class="row">


            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phòng ban <span class="require">*</span></label>
                    <select name="mapb" id="mapb" class="form-control select2me" autofocus="autofocus" required="required">
                        @foreach($m_pb as $pb)
                            <option value="{{$pb->mapb}}">{{$pb->tenpb}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Chức vụ <span class="require">*</span> </label>
                    <select name="macvcq" id="macvcq" class="form-control select2me" required="required">
                        @foreach($m_cvcq as $cv)
                            <option value="{{$cv->macvcq}}">{{$cv->tencv}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Mã công chức/viên chức </label>
                    {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!}
                </div>
            </div>


        </div>

    </div>
</div>
