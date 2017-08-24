<!--form1 thông tin cơ bản -->
<div id="tab1" class="tab-pane active" >
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
                    <label class="control-label">Mã công chức/viên chức </label>
                    {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!}
                </div>
            </div>
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
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Họ tên <span class="require">*</span></label>
                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control', 'required'=>'required'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tên gọi khác </label>
                    {!!Form::text('tenkhac', null, array('id' => 'tenkhac','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày sinh <span class="require">*</span></label>
                    <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" required="required" value="{{!isset($model)?'':$model->ngaysinh}}"/>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Giới tính</label>
                    {!! Form::select(
                    'gioitinh',
                    array(
                    'Nam' => 'Nam',
                    'Nữ' => 'Nữ'
                    ),null,
                    array('id' => 'gioitinh', 'class' => 'form-control'))
                    !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Dân tộc <span class="require">*</span></label>
                    {!! Form::select(
                    'dantoc',
                    $model_dt,null,
                    array('id' => 'dantoc', 'class' => 'form-control select2me','required'=>'required'))
                    !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tôn giáo </label>
                    {!!Form::text('tongiao', null, array('id' => 'tongiao','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số điện thoại </label>
                    {!!Form::text('sodt', null, array('id' => 'sodt','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Email </label>
                    {!!Form::text('email', null, array('id' => 'email','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số định danh cá nhân </label>
                    {!!Form::text('sodinhdanhcanhan', null, array('id' => 'sodinhdanhcanhan','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số CMTND </label>
                    {!!Form::text('socmnd', null, array('id' => 'socmnd','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày cấp </label>
                    <input type="date" id="ngaycap" name="ngaycap" class="form-control" value="{{!isset($model)?'':$model->ngaycap}}" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Nơi cấp </label>
                    {!!Form::text('noicap', null, array('id' => 'noicap','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Nơi ở hiện nay </label>
                    {!!Form::text('noio', null, array('id' => 'noio','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Hộ khẩu thường trú </label>
                    {!!Form::text('hktt', null, array('id' => 'hktt','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Ảnh đại diện </label>
                    @if(isset($model))
                        <p><img src="{{$model->anh!=''?url($model->anh):url('images/avatar/no-image.png')}}" width="90"></p>
                    @endif
                    {!!Form::file('anh', array('id' => 'anh'))!!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#create_hscb :submit').click(function(){
            var str = '',strb1='',strb2='';
            var ok = true;

            if($('#mapb').val()==''){
                strb1 += ' - Phòng ban \n';
                $('#mapb').parent().addClass('state-error');
                ok = false;
            }

            if($('#macvcq').val()==''){
                strb1 += '  - Chức vụ \n';
                $('#macvcq').parent().addClass('state-error');
                ok = false;
            }

            if(!$('#tencanbo').val()){
                strb1 += '  - Họ tên \n';
                $('#tencanbo').parent().addClass('state-error');
                ok = false;
            }

            if(!$('#ngaysinh').val()){
                strb1 += '  - Ngày sinh \n';
                $('#ngaysinh').parent().addClass('state-error');
                ok = false;
            }

            //Bước 2
            if(!$('#ngaybc').val()){
                strb2 += ' - Ngày biên chế, hợp đồng \n';
                $('#ngaybc').parent().addClass('state-error');
                ok = false;
            }



            //Kết quả
            if ( ok == false){
                if(strb1!='')
                    str+='Bước 1: \n '+strb1 ;
                if(strb2!='')
                    str+='Bước 2: \n '+strb2 ;

                alert('Các trường: \n' + str + 'Không được để trống');
                $("form").submit(function (e) {
                    e.preventDefault();
                });
            }
            else{
                $("form").unbind('submit').submit();
            }
        });
    });
</script>
<!--end form1 Thông tin cơ bản -->