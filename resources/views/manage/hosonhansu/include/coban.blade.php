<!--form1 thông tin cơ bản -->
<div id="tab1" class="tab-pane active" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Mã CCVC </label>
                    {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Họ tên <span class="require">*</span></label>
                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control', 'required'=>'required'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Khối/Tổ công tác</label>
                    <select name="mapb" id="mapb" class="form-control select2me">
                        @foreach(getPhongBan() as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Chức vụ(chức danh)</label>
                    {!!Form::select('macvcq',getChucVuCQ(false), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Giới tính</label>
                    {!! Form::select(
                    'gioitinh',array('Nam' => 'Nam','Nữ' => 'Nữ'),null,
                    array('id' => 'gioitinh', 'class' => 'form-control'))
                    !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày sinh</label>
                    <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" value="{{!isset($model)?'':$model->ngaysinh}}"/>
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
                    <label class="control-label">Phân loại theo dõi</label>
                    {!!Form::select('theodoi', getPhanLoaiCanBo_CongTac(), null, array('id' => 'theodoi','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Nơi công tác</label>
                    {!!Form::text('lvtd', null, array('id' => 'lvtd','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số thứ tự (sắp xếp)</label>
                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số chứng minh nhân dân</label>
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
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Nơi sinh-Xã </label>
                    {!!Form::text('nsxa', null, array('id' => 'nsxa','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Huyện </label>
                    {!!Form::text('nshuyen', null, array('id' => 'nshuyen','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tỉnh </label>
                    {!!Form::text('nstinh', null, array('id' => 'nstinh','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Quê quán-Xã </label>
                    {!!Form::text('qqxa', null, array('id' => 'qqxa','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Huyện </label>
                    {!!Form::text('qqhuyen', null, array('id' => 'qqhuyen','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tỉnh </label>
                    {!!Form::text('qqtinh', null, array('id' => 'qqtinh','class' => 'form-control'))!!}
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
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Nguồn kinh phí hưởng lương </label>
                    <select class="form-control" id="nguonkp" name="nguonkp" multiple="multiple">
                        @if(isset($a_nguonkp))
                            @foreach(getNguonKP(false) as $key=>$value)
                                <option value="{{$key}}" {{in_array($key,$a_nguonkp)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        @else
                            @foreach(getNguonKP(false) as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        @endif
                    </select>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Ảnh đại diện </label>
                    @if(isset($model))
                        <p><img id="str_anh" src="{{$model->anh!=''?url($model->anh):url('images/avatar/no-image.png')}}" width="90"></p>
                    @endif
                    {!!Form::file('anh', array('id' => 'anh'))!!}
                    <button style="margin-top: 5px" type="button" class="btn btn-danger btn-xs" onclick="xoaanh()"><i class="fa fa-times"></i>&nbsp;Xóa ảnh</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="bl_xoaanh" name="bl_xoaanh" value="false"/>
    <input type="hidden" id="lvhd" name="lvhd" value="{{isset($model)?$model->lvhd:''}}"/>
    <input type="hidden" id="manguonkp" name="manguonkp" value="{{isset($model)?$model->manguonkp:''}}"/>
    <input type="hidden" id="macanbo" name="macanbo" value="{{isset($model) ? $model->macanbo : ''}}"/>

</div>

<script>
    function xoaanh(){
        $("#str_anh").prop('src',"{{url('images/avatar/no-image.png')}}");
        $("#bl_xoaanh").val('true');
    }

    $(function(){
        //Multi select box
        $("#linhvuc").select2();
        $("#nguonkp").select2();
        $("#linhvuc").change(function(){
            $("#lvhd").val( $("#linhvuc").val());
        });

        $("#nguonkp").change(function(){
            $("#manguonkp").val( $("#nguonkp").val());
        });

        $('#create_hscb :submit').click(function(){
            var str = '';
            var ok = true;

            if(!$('#tencanbo').val()){
                str += '  - Họ tên \n';
                $('#tencanbo').parent().addClass('has-error');
                ok = false;
            }
            /*
            if(!$('#ngaysinh').val()){
                str += '  - Ngày sinh \n';
                $('#ngaysinh').parent().addClass('state-error');
                ok = false;
            }
             */
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