<div id="tab4b" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Nguồn kinh phí (Tổng số)</label>
                    {!!Form::text('nguonkp', null, array('id' => 'nguonkp','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tiết kiệm 10% chi thường xuyên</label>
                    {!!Form::text('tietkiem', null, array('id' => 'tietkiem','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Học phí</label>
                    {!!Form::text('hocphi', null, array('id' => 'hocphi','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Viện phí</label>
                    {!!Form::text('vienphi', null, array('id' => 'vienphi','class' => 'form-control'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Khác</label>
                    {!!Form::text('nguonthu', null, array('id' => 'nguonthu','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tiết kiệm chi theo nghị quyết 18, 19</label>
                    {!!Form::text('tietkiem1', null, array('id' => 'tietkiem1','class' => 'form-control'))!!}
                </div>
            </div>


        </div>
    </div>

</div>