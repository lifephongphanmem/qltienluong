<div id="tab4a" class="tab-pane">
    <div class="portlet-body" style="display: block;">
        <div id="nhucaukp" class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                NGUỒN THỰC HIỆN CẢI CÁCH TIỀN LƯƠNG
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% tăng/giảm thu NSĐP thực hiện</label>
                                            {!! Form::text('thuchien1', null, [
                                                'id' => 'thuchien1',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% tăng thu NSĐP dự toán 2023</label>
                                            {!! Form::text('dutoan', null, [
                                                'id' => 'dutoan',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% tăng thu NSĐP dự toán 2022</label>
                                            {!! Form::text('dutoan1', null, [
                                                'id' => 'dutoan1',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Số tiết kiệm 10% chi TX năm 2021</label>
                                            {!! Form::text('tietkiem2', null, [
                                                'id' => 'tietkiem2',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Số tiết kiệm 10% chi TX năm 2022</label>
                                            {!! Form::text('tietkiem1', null, [
                                                'id' => 'tietkiem1',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Số tiết kiệm 10% chi TX năm 2023</label>
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
                                        <h4 class="text-capitalize text-danger">Nguồn huy động từ các đơn vị tự đảm bảo
                                        </h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Học phí</label>
                                            {!! Form::text('huydongtx_hocphi_4a', null, [
                                                'id' => 'huydongtx_hocphi_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Viện phí</label>
                                            {!! Form::text('huydongtx_vienphi_4a', null, [
                                                'id' => 'huydongtx_vienphi_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn thu khác</label>
                                            {!! Form::text('huydongtx_khac_4a', null, [
                                                'id' => 'huydongtx_khac_4a',
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Tinh giản biên chế</label>
                                            {!! Form::text('tinhgiambc_4a', null, [
                                                'id' => 'tinhgiambc_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Sát nhập các đầu mối, cơ quan, đơn vị</label>
                                            {!! Form::text('satnhapdaumoi_4a', null, [
                                                'id' => 'satnhapdaumoi_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Thay đổi cơ chế tự chủ của đơn vị</label>
                                            {!! Form::text('thaydoicochetuchu_4a', null, [
                                                'id' => 'thaydoicochetuchu_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Sát nhập các xã không đủ điều kiện</label>
                                            {!! Form::text('satnhapxa_4a', null, [
                                                'id' => 'satnhapxa_4a',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn NSTW đã bổ sung</label>
                                            {!! Form::text('bosung', null, [
                                                'id' => 'bosung',
                                                'class' => 'form-control nguonkp text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn thực hiện cải cách tiền lương</label>
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
                            <div class="caption">
                                NHU CẦU KINH PHÍ TĂNG THÊM
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nhu cầu kinh phí theo nghị định số
                                                72/2018/NĐ-CP</label>
                                            {!! Form::text('tongnhucau2', null, [
                                                'id' => 'tongnhucau2',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nhu cầu kinh phí theo nghị định số
                                                38/2019/NĐ-CP</label>
                                            {!! Form::text('tongnhucau1', null, [
                                                'id' => 'tongnhucau1',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Quỹ trợ cấp tăng thêm đối với cán bộ xã đã
                                                nghỉ</label>
                                            {!! Form::text('nghihuu_4a', null, [
                                                'id' => 'nghihuu_4a',
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
                            <div class="caption">
                                NHU CẦU KINH PHÍ THỰC HIỆC PHỤ CẤP,TRỢ CẤP
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: block;">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí tăng, giảm do điều chỉnh địa bàn
                                                (131/QĐ-TTg và 582/QĐ-TTg)</label>
                                            {!! Form::text('diaban', null, [
                                                'id' => 'diaban',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí thực hiện chính sách tinh giản biên
                                                chế</label>
                                            {!! Form::text('tinhgiam', null, [
                                                'id' => 'tinhgiam',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí thực hiện chính sách nghỉ hưu trước
                                                tuổi</label>
                                            {!! Form::text('nghihuusom', null, [
                                                'id' => 'nghihuusom',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>




                                </div>

                                <div class="row">

                                    

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí thu hút (giảm do điều chỉnh huyện
                                                nghèo)</label>
                                            {!! Form::text('kpthuhut', null, [
                                                'id' => 'kpthuhut',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí ưu đãi (giảm do điều chỉnh huyện
                                                nghèo)</label>
                                            {!! Form::text('kpuudai', null, [
                                                'id' => 'kpuudai',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Kinh phí giảm ở cấp xã(do điều chỉnh số lượng cán bộ, công chức cấp xã; mức khoán phụ cấp đối với người hoạt động KCT)</label>
                                            {!! Form::text('kinhphigiamxa_4a', null, [
                                                'id' => 'kinhphigiamxa_4a',
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
