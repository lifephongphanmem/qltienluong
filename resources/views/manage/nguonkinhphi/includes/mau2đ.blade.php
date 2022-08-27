<!--form1 thông tin cơ bản -->
<div id="tab6" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng số đối tượng có mặt đến 31/12/2015</label>
                    {!!Form::text('tongsonguoi2015', null, array('id' => 'tongsonguoi2015','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng số đối tượng có mặt đến 01/7/2017</label>
                    {!!Form::text('tongsonguoi2017', null, array('id' => 'tongsonguoi2017','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Quỹ lương, phụ cấp tháng 7/2017</label>
                    {!!Form::text('quyluong', null, array('id' => 'quyluong','class' => 'form-control'))!!}
                </div>
            </div>
        </div>
    </div>

</div>


<!--end form1 Thông tin cơ bản -->