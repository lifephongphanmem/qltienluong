<div id="tab1" class="tab-pane active" >
    <div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">

                <input type="hidden" id="masodv" name="masodv" value="{{!isset($model)?'':$model->masodv}}"/>
                <input type="hidden" id="kdbhocphi" name="kdbhocphi" value="{{!isset($model)?'':$model->kdbhocphi}}"/>
                <input type="hidden" id="kdbvienphi" name="kdbvienphi"  value="{{!isset($model)?'':$model->kdbvienphi}}"/>
                <input type="hidden" id="kdbkhac" name="kdbkhac"  value="{{!isset($model)?'':$model->kdbkhac}}"/>
                <input type="hidden" id="dbhocphi" name="dbhocphi"  value="{{!isset($model)?'':$model->dbhocphi}}"/>
                <input type="hidden" id="dbvienphi" name="dbvienphi" value="{{!isset($model)?'':$model->dbvienphi}}" />
                <input type="hidden" id="dbkhac" name="dbkhac" value="{{!isset($model)?'':$model->dbkhac}}"/>
                <input type="hidden" id="tietkiemchi" name="tietkiemchi" value="{{!isset($model)?'':$model->tietkiemchi}}" />
                <input type="hidden" id="namns" name="namns" value="{{!isset($model)?'':$model->namns}}" />

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
                                    <div class="col-md-12">
                                        <label class="control-label">Căn cứ thông tư, quyết định</label>
                                        {!!Form::select('sohieu',getThongTuQD(), null, array('id' => 'sohieu','class' => 'form-control'))!!}
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% tăng/giảm thu NSĐP thực hiện 2018</label>
                                            {!!Form::text('thuchien', null, array('id' => 'thuchien','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">50% tăng thu NSĐP dự toán 2019 so 2018</label>
                                        {!!Form::text('dutoan19', null, array('id' => 'dutoan19','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">50% tăng thu NSĐP dự toán 2018 so 2017</label>
                                        {!!Form::text('dutoan18', null, array('id' => 'dutoan18','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Số tiết kiệm 10% năm 2017</label>
                                            {!!Form::text('tietkiem17', null, array('id' => 'tietkiem17','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Số tiết kiệm 10% năm 2018</label>
                                        {!!Form::text('tietkiem18', null, array('id' => 'tietkiem18','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Số tiết kiệm 10% năm 2019</label>
                                        {!!Form::text('tietkiem19', null, array('id' => 'tietkiem19','class' => 'form-control','readonly'=>'true', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn NSTW bổ sung 2019</label>
                                            {!!Form::text('bosung', null, array('id' => 'bosung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Nguồn cải cách tiền lương năm 2018 chuyển sang</label>
                                        {!!Form::text('caicach', null, array('id' => 'caicach','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    Sự nghiệp giáo dục
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19gd', null, array('id' => 'tietkiem19gd','class' => 'form-control tietkiem text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphigd', null, array('id' => 'kdbhocphigd','class' => 'form-control kdbhocphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphigd', null, array('id' => 'kdbvienphigd','class' => 'form-control kdbvienphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhacgd', null, array('id' => 'kdbkhacgd','class' => 'form-control kdbkhac text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchigd', null, array('id' => 'tietkiemchigd','class' => 'form-control tietkiemchi text-right', 'data-mask'=>'fdecimal'))!!}
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
                                    Sự nghiệp đào tạo
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19dt', null, array('id' => 'tietkiem19dt','class' => 'form-control tietkiem text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphidt', null, array('id' => 'kdbhocphidt','class' => 'form-control kdbhocphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphidt', null, array('id' => 'kdbvienphidt','class' => 'form-control kdbvienphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhacdt', null, array('id' => 'kdbkhacdt','class' => 'form-control kdbkhac text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchidt', null, array('id' => 'tietkiemchidt','class' => 'form-control tietkiemchi text-right', 'data-mask'=>'fdecimal'))!!}
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
                                    Sự nghiệp y tế
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19yte', null, array('id' => 'tietkiem19yte','class' => 'form-control tietkiem text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphiyte', null, array('id' => 'kdbhocphiyte','class' => 'form-control kdbhocphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphiyte', null, array('id' => 'kdbvienphiyte','class' => 'form-control kdbvienphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhacyte', null, array('id' => 'kdbkhacyte','class' => 'form-control kdbkhac text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchiyte', null, array('id' => 'tietkiemchiyte','class' => 'form-control tietkiemchi text-right', 'data-mask'=>'fdecimal'))!!}
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
                                    Sự nghiệp khác
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19khac', null, array('id' => 'tietkiem19khac','class' => 'form-control tietkiem text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphikhac', null, array('id' => 'kdbhocphikhac','class' => 'form-control kdbhocphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphikhac', null, array('id' => 'kdbvienphikhac','class' => 'form-control kdbvienphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhackhac', null, array('id' => 'kdbkhackhac','class' => 'form-control kdbkhac text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchikhac', null, array('id' => 'tietkiemchikhac','class' => 'form-control tietkiemchi text-right', 'data-mask'=>'fdecimal'))!!}
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
                                    Quản lý nhà nước, Đảng, đoàn thể
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19qlnn', null, array('id' => 'tietkiem19qlnn','class' => 'form-control tietkiem text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphiqlnn', null, array('id' => 'kdbhocphiqlnn','class' => 'form-control kdbhocphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphiqlnn', null, array('id' => 'kdbvienphiqlnn','class' => 'form-control kdbvienphi text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhacqlnn', null, array('id' => 'kdbkhacqlnn','class' => 'form-control kdbkhac text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchiqlnn', null, array('id' => 'tietkiemchiqlnn','class' => 'form-control tietkiemchi text-right', 'data-mask'=>'fdecimal'))!!}
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
                                    Cán bộ, công chức cấp xã
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
                                                <label class="control-label">Tiết kiệm 10%</label>
                                                {!!Form::text('tietkiem19xa', null, array('id' => 'tietkiem19xa','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Học phí</label>
                                                {!!Form::text('kdbhocphixa', null, array('id' => 'kdbhocphixa','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Viện phí</label>
                                                {!!Form::text('kdbvienphixa', null, array('id' => 'kdbvienphixa','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khác</label>
                                                {!!Form::text('kdbkhacxa', null, array('id' => 'kdbkhacxa','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tiết kiệm chi NQ 18,19</label>
                                                {!!Form::text('tietkiemchixa', null, array('id' => 'tietkiemchixa','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
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
        </div>
    </div>
    </div>
    </div>
    <script>

        $('.tietkiem').change(function(){
            var tong = 0;
            $('.tietkiem').each(function () {
                tong += getdl($(this).val());
            });
            $('#tietkiem19').val(tong);
        })

        $('.kdbhocphi').change(function(){
            var tong = 0;
            $('.kdbhocphi').each(function () {
                tong += getdl($(this).val());
            });
            $('#kdbhocphi').val(tong);
        })

        $('.kdbvienphi').change(function(){
            var tong = 0;
            $('.kdbvienphi').each(function () {
                tong += getdl($(this).val());
            });
            $('#kdbvienphi').val(tong);
        })
        $('.kdbkhac').change(function(){
            var tong = 0;
            $('.kdbkhac').each(function () {
                tong += getdl($(this).val());
            });
            $('#kdbkhac').val(tong);
        })
        $('.tietkiemchi').change(function(){
            var tong = 0;
            $('.tietkiemchi').each(function () {
                tong += getdl($(this).val());
            });
            $('#tietkiemchi').val(tong);
        })

    </script>
<script>
    $(function(){
        $("#sohieu").change(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/nguon_kinh_phi/get_thongtu',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    sohieu: $("#sohieu").val()
                },
                dataType: 'JSON',
                success: function (data) {
                    $("#namns").val(data.namdt);
                }
            });
        });
    });
</script>
