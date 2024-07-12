<div id="tab2e" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số lượng đơn vị đến 31/12/2017</label>
                    {!!Form::text('tongsodonvi1', null, array('id' => 'tongsodonvi1','class' => 'form-control','data-mask' => 'fdecimal'))!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số lượng đơn vị đến 31/12/2019</label>
                    {!!Form::text('tongsodonvi2', null, array('id' => 'tongsodonvi2','class' => 'form-control','data-mask' => 'fdecimal'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Kinh phí tiết kiệm được từ việc thay đổi cơ chế tự chủ trong 1 tháng</label>
                    {!!Form::text('quy_tuchu', null, array('id' => 'quy_tuchu','class' => 'form-control','data-mask' => 'fdecimal'))!!}
                </div>
            </div>


        </div>
    </div>

</div>