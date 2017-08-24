<!--form1 thông tin cơ bản -->
<div id="tab2" class="tab-pane" >
    <div class="form-horizontal">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ngày tuyển dụng </label>

                    <div class="col-sm-8 controls">
                        <input type="date" name="ngaytd" id="ngaytd" class="form-control" autofocus="autofocus" value="{{!isset($model)?'':$model->ngaytd}}"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Cơ quan tuyển dụng </label>

                    <div class="col-sm-8">
                        {!!Form::text('cqtd', null, array('id' => 'cqtd','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Hình thức tuyển dụng </label>

                    <div class="col-sm-8 controls">
                        {!!Form::text('httd', null, array('id' => 'httd','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Lĩnh vực tuyển dụng </label>

                    <div class="col-sm-8">
                        <select class="form-control select2me" id="lvtd" name="lvtd">
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
                    <label class="col-sm-4 control-label">Công việc chính </label>

                    <div class="col-sm-8">
                        {!!Form::text('cvcn', null, array('id' => 'cvcn','class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Sở trường công tác </label>

                    <div class="col-sm-8">
                        {!!Form::text('stct', null, array('id' => 'stct','class' => 'form-control'))!!}
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

          <!-- Hết -->
    </div>
</div>

<!--end form1 Thông tin cơ bản -->