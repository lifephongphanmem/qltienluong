<div id="tab4a" class="tab-pane">
    <div class="portlet-body" style="display: block;">
        <div id="nhucaukp" class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                               A - NGUỒN THỰC HIỆN CẢI CÁCH TIỀN LƯƠNG
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">70% tăng thu NSĐP thực hiện năm 2023</label>
                                            {!! Form::text('thuchien1', null, [
                                                'id' => 'thuchien1',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">50% tăng thu NSĐP dự toán 2024 so với dự toán năm 2023</label>
                                            {!! Form::text('thuchien2', null, [
                                                'id' => 'thuchien2',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Số tiết kiệm 10% chi TX năm 2024</label>
                                            {!! Form::text('tietkiem', null, [
                                                'id' => 'tietkiem',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="text-capitalize text-danger">Nguồn huy động từ các đơn vị chưa tự đảm
                                            bảo chi thường xuyên</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Học phí</label>
                                            {!! Form::text('huydongktx_hocphi_4a', null, [
                                                'id' => 'huydongktx_hocphi_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Viện phí</label>
                                            {!! Form::text('huydongktx_vienphi_4a', null, [
                                                'id' => 'huydongktx_vienphi_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn thu khác</label>
                                            {!! Form::text('huydongktx_khac_4a', null, [
                                                'id' => 'huydongktx_khac_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">50% phần NSNN giảm chi hỗ trợ hoạt động thường xuyên trong lĩnh vực hành chính và các đơn vị sự nghiệp công lập</label>
                                            {!! Form::text('nsnngiam', null, [
                                                'id' => 'nsnngiam',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn Cải cách tiền lương năm 2023 chuyển
                                                sang năm 2024</label>
                                            {!! Form::text('caicach', null, [
                                                'id' => 'caicach',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
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
                            <div class="caption text-uppercase">
                                B.I - Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số 24/2023/NĐ-CP và Nghị định số 42/2023/NĐ-CP
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Tổng nhu cầu kinh phí tăng thêm</label>
                                            {!! Form::text('tongnhucau1', null, [
                                                'id' => 'tongnhucau1',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
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
                            <div class="caption text-uppercase">
                                B.II - Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số 73/2024/NĐ-CP 
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Quỹ trợ cấp tăng thêm đối với cán bộ xã đã
                                                nghỉ</label>
                                            {!! Form::text('nghihuu_4a', null, [
                                                'id' => 'nghihuu_4a',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                                'readonly',
                                            ]) !!}
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
                                B.III - NHU CẦU KINH PHÍ THỰC HIỆC PHỤ CẤP,TRỢ CẤP 
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí thực hiện chính sách tinh giản biên
                                                chế năm 2024</label>
                                            {!! Form::text('tinhgiam', null, [
                                                'id' => 'tinhgiam',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí thực hiện chính sách nghỉ hưu trước
                                                tuổi năm 2024</label>
                                            {!! Form::text('nghihuusom', null, [
                                                'id' => 'nghihuusom',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!-- Dùng thu hút để lưu =>ko pải thêm trường -->
                                            <label class="control-label">Nhu cầu kinh phí trả thực hiện chế độ thù lao
                                                đối với người đã nghỉ hưu lanh đạo Hội đặc thù</label>
                                            {!! Form::text('kpuudai', null, [
                                                'id' => 'kpuudai',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Nhu cầu kinh phí tăng thêm thực hiện chế độ trợ
                                                cấp lần đầu nhận công tác vùng ĐBKK</label>
                                            {!! Form::text('kinhphigiamxa_4a', null, [
                                                'id' => 'kinhphigiamxa_4a',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Các khoản phụ cấp, trợ cấp khác</label>
                                            {!! Form::text('nhucau', null, [
                                                'id' => 'nhucau',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
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
