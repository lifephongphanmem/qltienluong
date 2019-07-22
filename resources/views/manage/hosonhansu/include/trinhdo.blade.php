<!--form2 thông tin trình độ hiện tại của cán bộ -->
<div id="tab6" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Trình độ chuyên môn </label>
                    {!!Form::text('tdcm', null, array('id' => 'tdcm','class' => 'form-control','autofocus'=>'autofocus'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Chuyên ngành đào tạo </label>
                    {!!Form::text('chuyennganh', null, array('id' => 'chuyennganh','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Hình thức đào tạo </label>
                    {!!Form::text('hinhthuc', null, array('id' => 'hinhthuc','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Nơi đào tạo </label>
                    {!!Form::text('noidt', null, array('id' => 'noidt','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Trình độ văn hóa </label>
                    {!!Form::text('tdgdpt', null, array('id' => 'tdgdpt','class' => 'form-control',))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Trình độ tin học </label>
                    {!!Form::text('trinhdoth', null, array('id' => 'trinhdoth','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngoại ngữ </label>
                    {!!Form::text('ngoaingu', null, array('id' => 'ngoaingu','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Trình độ ngoại ngữ </label>
                    {!!Form::text('trinhdonn', null, array('id' => 'trinhdonn','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Trình độ chính trị </label>
                    {!!Form::text('llct', null, array('id' => 'llct','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Quản lý nhà nước </label>
                    {!!Form::text('qlnhanuoc', null, array('id' => 'qlnhanuoc','class' => 'form-control'))!!}
                </div>
            </div>
        </div>
    </div>
</div>

<!--end form2 -->