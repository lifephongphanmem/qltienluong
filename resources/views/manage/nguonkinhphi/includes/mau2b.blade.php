<!--form1 thông tin cơ bản -->
<div id="tab2" class="tab-pane ">
    <div class="form-body">
                <div class="portlet-body" style="display: block;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold" style="margin-left: 15px">Chức danh </label>
                                {{-- {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!} --}}
                                <p class="form-control" style="border: none; margin-top:15px">Nguyên bí thư, chủ tịch
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Tổng số người nghỉ việc hưởng trợ cấp hàng
                                    tháng đến 01/7/2019 </label>
                                {!! Form::text('tongsonguoi1', null, ['id' => 'tongsonguoi1', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Quỹ trợ cấp 1 tháng theo quy định tại nghị
                                    định số 88/2018/NĐ-CP</label>
                                {!! Form::text('quy1_1', null, ['id' => 'quy1_1', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Quỹ trợ cấp 1 tháng theo quy định tại nghị
                                    định số 44/2019/NĐ-CP</label>
                                {!! Form::text('quy2_1', null, ['id' => 'quy2_1', 'class' => 'form-control']) !!}
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {{-- <label class="control-label"> </label> --}}
                                {{-- {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!} --}}
                                <p class="form-control" style="border: none">Nguyên Phó bí thư, phó chủ tịch, Thường
                                    trực
                                    Đảng uỷ, Uỷ viên, Thư ký UBND Thư ký HĐND, xã đội trưởng
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> </label>
                                {!! Form::text('tongsonguoi2', null, ['id' => 'tongsonguoi2', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                {!! Form::text('quy1_2', null, ['id' => 'quy1_2', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                {!! Form::text('quy2_2', null, ['id' => 'quy2_2', 'class' => 'form-control']) !!}
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> </label>
                                {{-- {!!Form::text('macongchuc', null, array('id' => 'macongchuc','class' => 'form-control'))!!} --}}
                                <p class="form-control" style="border: none">Các chức danh còn lại
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> </label>
                                {!! Form::text('tongsonguoi3', null, ['id' => 'tongsonguoi3', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                {!! Form::text('quy1_3', null, ['id' => 'quy1_3', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                {!! Form::text('quy2_3', null, ['id' => 'quy2_3', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
    </div>
</div>
    <!--end form1 Thông tin cơ bản -->
