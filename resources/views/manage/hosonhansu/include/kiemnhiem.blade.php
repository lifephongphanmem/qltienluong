<!--form1 thông tin cơ bản -->
<div id="tab4" class="tab-pane" >
    <div class="form-horizontal">
        <div class="row">
            <div class="col-md-offset-10 col-md-2">


                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    Thêm mới <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_chvu()"><i class="fa fa-plus"></i>&nbsp;Chức danh/Chức vụ</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_kct()"><i class="fa fa-plus"></i>&nbsp;Cán bộ không CT</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_dbhdnd()"><i class="fa fa-plus"></i>&nbsp;Đại biểu hội đồng ND</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_qs()"><i class="fa fa-plus"></i>&nbsp;Cán bộ quân sự</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_cuv()"><i class="fa fa-plus"></i>&nbsp;Cấp ủy viên</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_cd()"><i class="fa fa-plus"></i>&nbsp;Công tác cộng đồng</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_mc()"><i class="fa fa-plus"></i>&nbsp;Văn phòng một cửa</button>
                    </li>
                    <li>
                        <button style="border-width: 0px" type="button" class="btn btn-default" onclick="add_tn()"><i class="fa fa-plus"></i>&nbsp;Đội tình nguyện</button>
                    </li>
                </ul>
            </div>
        </div>
            </br>
        <div class="row" id="dskn">
            <div class="col-md-12">
                <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">STT</th>
                        <th class="text-center">Phân loại</th>
                        <th class="text-center">Chức vụ</br>kiêm nhiệm</th>
                        <th class="text-center">Hệ</br>số</br>lương</th>
                        <th class="text-center">Hệ</br>số</br>phụ</br>cấp</th>
                        <th class="text-center">Phụ</br>cấp</br>chức</br>vụ</th>
                        <th class="text-center">Phụ</br>cấp</br>trách</br>nhiệm</th>
                        <th class="text-center">Phụ</br>cấp</br>kiêm</br>nhiệm</th>
                        <th class="text-center">Phụ</br>cấp</br>đặc</br>thù</th>
                        <th class="text-center">Phụ</br>cấp</br>một</br>cửa</th>
                        <th class="text-center">Phụ</br>cấp</br>điện</br>thoại</th>
                        <th class="text-center">Phụ</br>cấp</br>khác</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                @if(isset($model_kn))
                    @foreach($model_kn as $key=>$value)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="text-center">{{$value->tenphanloai}}</td>
                            <td class="text-center">{{$value->tenchucvu}}</td>
                            <td class="text-right">{{$value->heso}}</td>
                            <td class="text-right">{{$value->hesopc}}</td>
                            <td class="text-right">{{$value->pccv}}</td>
                            <td class="text-right">{{$value->pctn}}</td>
                            <td class="text-right">{{$value->pckn}}</td>
                            <td class="text-right">{{$value->pcdbn}}</td>
                            <td class="text-right">{{$value->pcd}}</td>
                            <td class="text-right">{{$value->pcdith}}</td>
                            <td class="text-right">{{$value->pck}}</td>
                            <td>
                                <button type="button" class="btn btn-default btn-xs mbs" onclick="edit_kn({{$value->id}});"><i class="fa fa-edit"></i>&nbsp;Sửa</button>
                                <button type="button" class="btn btn-default btn-xs mbs" onclick="deleteRow({{$value->id}})" ><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@include('manage.hosocanbo.include.modal_kiemnhiem')
<!--end form5  -->