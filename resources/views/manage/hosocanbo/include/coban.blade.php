<!--form1 thông tin cơ bản -->
<div id="tab1" class="tab-pane active" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Mã công chức/viên chức </label>
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
                    <label class="control-label">Chức vụ (chức danh)</label>
                    {!!Form::select('macvcq',getChucVuCQ(false), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Ngày sinh</label>
                    <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" value="{{!isset($model)?'':$model->ngaysinh}}"/>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Giới tính</label>
                    {!! Form::select(
                        'gioitinh',array('Nam' => 'Nam','Nữ' => 'Nữ'),null,
                        array('id' => 'gioitinh', 'class' => 'form-control'))
                    !!}
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
                    <label class="control-label">Số thứ tự (sắp xếp)</label>
                    {!!Form::text('stt', null, array('id' => 'stt','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Nơi công tác</label>
                    {!!Form::text('lvtd', null, array('id' => 'lvtd','class' => 'form-control'))!!}
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
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số tài khoản ngân hàng</label>
                    {!!Form::text('sotk', null, array('id' => 'sotk','class' => 'form-control'))!!}
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
    <input type="hidden" id="manguonkp" name="manguonkp" value="{{isset($model)?$model->manguonkp:''}}"/>
    <input type="hidden" id="macanbo" name="macanbo" value="{{isset($model) ? $model->macanbo : ''}}"/>

</div>

<script>
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