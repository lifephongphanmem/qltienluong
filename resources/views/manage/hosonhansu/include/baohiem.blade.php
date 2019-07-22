<!--form6 thông tin cơ bản -->
<div id="tab2" class="tab-pane active" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phân loại công tác</label>
                    <select class="form-control select2me" name="mact" id="mact" required="required">
                        @foreach($model_nhomct as $kieuct)
                            <optgroup label="{{$kieuct->tencongtac}}">
                                <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                @foreach($mode_ct as $ct)
                                    <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phân loại cán bộ</label>
                    <select class="form-control select2me" name="sunghiep" id="sunghiep" required="required">
                        <option value="Công chức">Công chức</option>
                        <option value="Viên chức">Viên chức</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Nộp bảo hiểm</label>
                    {!! Form::select('baohiem',getNopBaoHiem(),null,array('id' => 'baohiem', 'class' => 'form-control select2me'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số người phụ thuộc</label>
                    {!!Form::text('nguoiphuthuoc', null, array('id' => 'nguoiphuthuoc','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Bảo hiểm xã hội</label>
                    {!!Form::text('bhxh', null, array('id' => 'bhxh','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Bảo hiểm y tế</label>
                    {!!Form::text('bhyt', null, array('id' => 'bhyt','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Kinh phí công đoàn</label>
                    {!!Form::text('kpcd', null, array('id' => 'kpcd','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Bảo hiểm thất nghiệp</label>
                    {!!Form::text('bhtn', null, array('id' => 'bhtn','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">BHXH đơn vị nộp</label>
                    {!!Form::text('bhxh_dv', null, array('id' => 'bhxh_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">BHYT đơn vị nộp</label>
                    {!!Form::text('bhyt_dv', null, array('id' => 'bhyt_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">KPCĐ đơn vị nộp</label>
                    {!!Form::text('kpcd_dv', null, array('id' => 'kpcd_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">BHTN đơn vị nộp</label>
                    {!!Form::text('bhtn_dv', null, array('id' => 'bhtn_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Các phụ cấp không nộp bảo hiểm</label>
                    {!! Form::select('khongnopbaohiem[]',$a_pc_bh,null,array('id' => 'khongnopbaohiem','class' => 'form-control select2me','multiple'=>'multiple')) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getbaohiem() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/nghiep_vu/ho_so/get_congtac',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                mact: $("#mact").val()
            },
            dataType: 'JSON',
            success: function (data) {
                $("#bhxh").val(data.bhxh);
                $("#bhyt").val(data.bhyt);
                $("#bhtn").val(data.bhtn);
                $("#kpcd").val(data.kpcd);
                $("#bhxh_dv").val(data.bhxh_dv);
                $("#bhyt_dv").val(data.bhyt_dv);
                $("#bhtn_dv").val(data.bhtn_dv);
                $("#kpcd_dv").val(data.kpcd_dv);
                var sunghiep = $("#sunghiep").val();
                if(sunghiep == 'Công chức'){
                    $("#bhtn").val(0);
                    $("#bhtn_dv").val(0);
                }
            }
        });
    }

    function getbaohiem_cv() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/nghiep_vu/ho_so/get_chucvu_bh',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                macvcq: $("#macvcq").val()
            },
            dataType: 'JSON',
            success: function (data) {

                if(data.ttdv == '1'){
                    $("#bhtn").val(0);
                    $("#bhtn_dv").val(0);
                }
            }
        });
    }

    $(function(){
        $("#macvcq").change(function(){
            getbaohiem_cv();

        });

        $("#mact").change(function(){
            getbaohiem();

        });

        $("#baohiem").change(function(){
            var baohiem = $("#baohiem").val();
            if(baohiem ==0){
                $("#bhxh").val(0);
                $("#bhyt").val(0);
                $("#bhtn").val(0);
                $("#kpcd").val(0);
                $("#bhxh_dv").val(0);
                $("#bhyt_dv").val(0);
                $("#bhtn_dv").val(0);
                $("#kpcd_dv").val(0);
            }else {
                getbaohiem();
            }
        });

        $("#sunghiep").change(function(){
            var sunghiep = $("#sunghiep").val();
            if(sunghiep == 'Công chức'){
                $("#bhtn").val(0);
                $("#bhtn_dv").val(0);
            }else{
                getbaohiem();
            }
        });
    });
</script>
<!--end form1 Thông tin cơ bản -->