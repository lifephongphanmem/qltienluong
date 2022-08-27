<div id="tab1" class="tab-pane active">
    <div class="portlet-body" style="display: block;">
        <div id="nhucaukp" class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tổng nhu cầu kinh phí năm
                            {{ $nam - 2 }}</label>
                        {!! Form::text('tongnhucau2', null, [
                            'id' => 'tongnhucau2',
                            'class' => 'form-control nhucaukp text-right',
                            'data-mask' => 'fdecimal',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tổng nhu cầu kinh phí năm
                            {{ $nam - 1 }}</label>
                        {!! Form::text('tongnhucau1', null, [
                            'id' => 'tongnhucau1',
                            'class' => 'form-control nhucaukp text-right',
                            'data-mask' => 'fdecimal',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Lương, phụ cấp</label>
                        {!! Form::text('luongphucap', null, [
                            'id' => 'luongphucap',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Phí hoạt động ĐBHĐND</label>
                        {!! Form::text('daibieuhdnd', null, [
                            'id' => 'daibieuhdnd',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Trợ cấp bộ đã nghỉ hưu</label>
                        {!! Form::text('nghihuu', null, [
                            'id' => 'nghihuu',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Cán bộ không chuyên trách</label>
                        {!! Form::text('canbokct', null, [
                            'id' => 'canbokct',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Phụ cấp trách nhiệm cấp ủy</label>
                        {!! Form::text('uyvien', null, [
                            'id' => 'uyvien',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class=" control-label">Bồi dưỡng hoạt động cấp ủy</label>
                        {!! Form::text('boiduong', null, [
                            'id' => 'boiduong',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class=" control-label">Các khoản nộp theo lương</label>
                        {!! Form::text('baohiem', null, [
                            'id' => 'baohiem',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Tổng số</label>
                        {!! Form::text('nhucaukp', null, [
                            'id' => 'nhucaukp',
                            'class' => 'form-control text-right',
                            'data-mask' => 'fdecimal',
                            'readonly' => 'true',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                Thông tin nhu cầu thực hiện một số loại phụ cấp, trợ cấp
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
                                            <label class="control-label">Hỗ trợ chênh lệch cho người có thu nhập
                                                thấp</label>
                                            {!! Form::text('thunhapthap', null, [
                                                'id' => 'thunhapthap',
                                                'class' => 'form-control nhucaupc text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
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

                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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

                                    <div class="col-md-6">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Tổng số</label>
                                            {!! Form::text('nhucaupc', null, [
                                                'id' => 'nhucaupc',
                                                'class' => 'form-control text-right',
                                                'data-mask' => 'fdecimal',
                                                'readonly' => 'true',
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

            {{-- <div class="row">
                <div class="col-md-12">
                    <table id="sample_3" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3%">S</br>T</br>T</th>
                                <th class="text-center">Phân loại công tác</th>
                                <th class="text-center">Lương</br>phụ cấp</th>
                                <th class="text-center">Phí hoạt</br>động</br>ĐBHĐND</th>
                                <th class="text-center">Trợ cấp</br>bộ đã</br>nghỉ hưu</th>
                                <th class="text-center">Cán bộ</br>không</br>chuyên</br>trách</th>
                                <th class="text-center">Phụ cấp</br>trách</br>nhiệm</br>cấp ủy</th>
                                <th class="text-center">Bồi dưỡng</br>hoạt động</br>cấp ủy</th>
                                <th class="text-center">Các khoản</br>nộp theo</br>lương</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($model_ct as $key => $value)
                                <tr class="{{ getTextStatus($value->trangthai) }}">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $a_ct[$value->mact] }}</td>
                                    <td class="text-right">{{ dinhdangso($value->luongphucap) }}
                                    </td>
                                    <td class="text-right">{{ dinhdangso($value->daibieuhdnd) }}
                                    </td>
                                    <td class="text-right">{{ dinhdangso($value->nghihuu) }}</td>
                                    <td class="text-right">{{ dinhdangso($value->canbokct) }}</td>
                                    <td class="text-right">{{ dinhdangso($value->uyvien) }}</td>
                                    <td class="text-right">{{ dinhdangso($value->boiduong) }}</td>
                                    <td class="text-right">{{ dinhdangso($value->baohiem) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>


    </div>
</div>
