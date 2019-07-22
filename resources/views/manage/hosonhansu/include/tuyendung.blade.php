<!--form1 thông tin cơ bản -->
<div id="tab5" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày tuyển dụng </label>
                    <input type="date" name="ngaytd" id="ngaytd" class="form-control" autofocus="autofocus" value="{{!isset($model)?'':$model->ngaytd}}"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Cơ quan tuyển dụng </label>
                    {!!Form::text('cqtd', null, array('id' => 'cqtd','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Hình thức tuyển dụng </label>
                    {!!Form::text('httd', null, array('id' => 'httd','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Lĩnh vực tuyển dụng </label>
                    {!!Form::select('lvtd',$m_linhvuc, null, array('id' =>'lvtd', 'class' => 'form-control select2me'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Công việc chính </label>
                    {!!Form::text('cvcn', null, array('id' => 'cvcn','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Sở trường công tác </label>
                    {!!Form::text('stct', null, array('id' => 'stct','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày biên chế </label>
                    <input type="date" name="ngaybc" id="ngaybc" class="form-control" value="{{!isset($model)?'':$model->ngaybc}}" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày về cơ quan </label>
                    <input type="date" name="ngayvao" id="ngayvao" class="form-control" value="{{!isset($model)?'':$model->ngayvao}}" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Lĩnh vực công tác </label>
                    {!!Form::select('lvhd',$m_linhvuc, null, array('id' =>'lvhd', 'class' => 'form-control select2me'))!!}
                </div>
            </div>
        </div>
    </div>
</div>

<!--end form1 Thông tin cơ bản -->