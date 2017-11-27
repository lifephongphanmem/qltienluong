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
            <!--
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phòng ban</label>
                    <select name="mapb" id="mapb" class="form-control select2me">
                        @foreach($m_pb as $pb)
                            <option value="{{$pb->mapb}}">{{$pb->tenpb}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Chức vụ</label>
                    <select name="macvcq" id="macvcq" class="form-control select2me">
                        @foreach($m_cvcq as $cv)
                            <option value="{{$cv->macvcq}}">{{$cv->tencv}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Họ tên <span class="require">*</span></label>
                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control', 'required'=>'required'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <!--
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tên gọi khác </label>
                                {!!Form::text('tenkhac', null, array('id' => 'tenkhac','class' => 'form-control'))!!}
                            </div>
                        </div>
                        -->
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
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày biên chế (trúng cử)</label>
                    <input type="date" name="ngaybc" id="ngaybc" class="form-control" value="{{!isset($model)?'':$model->ngaybc}}" />
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày về cơ quan </label>
                    <input type="date" name="ngayvao" id="ngayvao" class="form-control" value="{{!isset($model)?'':$model->ngayvao}}" />
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Sự nghiệp cán bộ</label>
                    <select class="form-control select2me" name="sunghiep" id="sunghiep" required="required">
                        <option value="Công chức">Công chức</option>
                        <option value="Viên chức">Viên chức</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phân loại công tác</label>
                    <select class="form-control select2me" name="mact" id="mact" required="required">
                        @foreach($model_nhomct as $kieuct)
                            <optgroup label="{{$kieuct->tencongtac}}">
                                <?php
                                $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                ?>
                                @foreach($mode_ct as $ct)
                                    <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Lĩnh vực công tác </label>
                    <select class="form-control" id="linhvuc" name="linhvuc" multiple="multiple">
                        @if(isset($a_linhvuc))
                            @foreach($m_linhvuc as $key=>$value)
                                <option value="{{$key}}" {{in_array($key,$a_linhvuc)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        @else
                            @foreach($m_linhvuc as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-6">
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
    <input type="hidden" id="lvhd" name="lvhd" value="{{isset($model)?$model->lvhd:''}}"/>

</div>

<script>
    $(function(){
        //Multi select box
        $("#linhvuc").select2();
        $("#linhvuc").change(function(){
            $("#lvhd").val( $("#linhvuc").val());
        });
        $('#create_hscb :submit').click(function(){
            var str = '';
            var ok = true;

            if(!$('#tencanbo').val()){
                str += '  - Họ tên \n';
                $('#tencanbo').parent().addClass('state-error');
                ok = false;
            }

            if(!$('#ngaysinh').val()){
                str += '  - Ngày sinh \n';
                $('#ngaysinh').parent().addClass('state-error');
                ok = false;
            }

            //Kết quả
            if ( ok == false){
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