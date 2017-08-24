<!--form1 thông tin cơ bản -->
<div id="tab5" class="tab-pane" >
    <div class="form-horizontal">



        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngày vào Đảng </label>

                    <div class="col-sm-8 controls">
                        <input type="date" class="form-control" name="ngayvd" id="ngayvd" value="{{!isset($model)?'':$model->ngayvd}}"/>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngày vào chính thức </label>

                    <div class="col-sm-8 controls">
                        <input type="date" class="form-control" name="ngayvdct" id="ngayvdct" value="{{!isset($model)?'':$model->ngayvdct}}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nơi kết nạp Đảng</label>

                    <div class="col-sm-8">
                        {!!Form::text('noikn', null, array('id' => 'noikn','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Chức vụ Đảng </label>

                    <div class="col-sm-8">
                        <select class="form-control select2me" name="macvd" id="macvd">
                            <option value="">-- Chọn chức vụ --</option>
                            @foreach($m_cvd as $cv)
                                <option value="{{$cv->macvd}}">{{$cv->tencv}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngày biên chế <span class="require">*</span></label>

                    <div class="col-sm-8">
                        <input type="date" name="ngaybc" id="ngaybc" class="form-control" required="required" value="{{!isset($model)?'':$model->ngaybc}}" />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngày về cơ quan </label>

                    <div class="col-sm-8">
                        <input type="date" name="ngayvao" id="ngayvao" class="form-control" value="{{!isset($model)?'':$model->ngayvao}}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Sự nghiệp cán bộ <span class="require">*</span></label>

                    <div class="col-sm-8">
                        <select class="form-control select2me" name="sunghiep" id="sunghiep" required="required">
                            <option value="Công chức">Công chức</option>
                            <option value="Viên chức">Viên chức</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Lĩnh vực công tác </label>

                    <div class="col-sm-8">
                        <select class="form-control select2me" id="lvhd" name="lvhd">
                            <option value="Giáo dục">Giáo dục</option>
                            <option value="Y tế">Y tế</option>
                            <option value="Nghiên cứu khoa học">Nghiên cứu khoa học</option>
                            <option value="Quản lý nhà nước">Quản lý nhà nước</option>
                            <option value="Văn hóa thể thao">Văn hóa thể thao</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Phân loại công tác <span class="require">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control select2me" name="tenct" id="tenct" required="required">
                            @foreach($model_kieuct as $kieuct)
                                <optgroup label="{{$kieuct->kieuct}}">
                                    <?php
                                    $mode_ct=$model_tenct->where('kieuct',$kieuct->kieuct);
                                    ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->tenct}}">{{$ct->tenct}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Trình độ chuyên môn </label>

                    <div class="col-sm-8 controls">
                        {!!Form::text('tdcm', null, array('id' => 'tdcm','class' => 'form-control','autofocus'=>'autofocus'))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Chuyên ngành đào tạo </label>

                    <div class="col-sm-8">
                        {!!Form::text('chuyennganh', null, array('id' => 'chuyennganh','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Hình thức đào tạo </label>

                    <div class="col-sm-8 controls">
                        {!!Form::text('hinhthuc', null, array('id' => 'hinhthuc','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nơi đào tạo </label>

                    <div class="col-sm-8">
                        {!!Form::text('noidt', null, array('id' => 'noidt','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Trình độ văn hóa <span
                                class="require"></span></label>

                    <div class="col-sm-8 controls">
                        {!!Form::text('tdgdpt', null, array('id' => 'tdgdpt','class' => 'form-control',))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Trình độ tin học </label>

                    <div class="col-sm-8 controls">
                        {!!Form::text('trinhdoth', null, array('id' => 'trinhdoth','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngoại ngữ </label>

                    <div class="col-sm-8">
                        {!!Form::text('ngoaingu', null, array('id' => 'ngoaingu','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Trình độ ngoại ngữ </label>

                    <div class="col-sm-8">
                        {!!Form::text('trinhdonn', null, array('id' => 'trinhdonn','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Trình độ chính trị </label>

                    <div class="col-sm-8">
                        {!!Form::text('llct', null, array('id' => 'llct','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Quản lý nhà nước </label>

                    <div class="col-sm-8">
                        {!!Form::text('qlnhanuoc', null, array('id' => 'qlnhanuoc','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end form5  -->