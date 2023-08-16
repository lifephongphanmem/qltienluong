<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi extends Model
{
    protected $table = 'nguonkinhphi';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'sohieu',
        'manguonkp',
        'noidung',
        'namns',
        'linhvuchoatdong',
        'sobiencheduocgiao',
        'nhucau',
        'luongphucap',
        'daibieuhdnd',
        'nghihuu',
        'canbokct',
        'uyvien',
        'boiduong',
        'thunhapthap',
        'diaban',


        'baohiem',
        'nguonkp',

        'madv',
        'macqcq',
        'madvbc',
        'maphanloai',
        'trangthai',
        'ngayguidv',
        'nguoiguidv',
        'ngayguih',
        'nguoiguih',
        'lydo',
        //thêm mới theo thông tư 46/2019

        'bosung',
        'caicach',

        'luongchuyentrach', //thừa
        'luongkhongchuyentrach', //thừa
        'tongnhucau1', //tổng nhu cầu kinh trước 1 năm
        'tongnhucau2', //tổng nhu cầu kinh trước 2 năm        

        //mẫu 2b
        'tongsonguoi1', //mẫu 2b
        'quy1_1', //mẫu 2b
        'quy1_2', //mẫu 2b
        'quy1_3', //mẫu 2b
        'tongsonguoi2', //mẫu 2b
        'quy2_1', //mẫu 2b
        'quy2_2', //mẫu 2b
        'quy2_3', //mẫu 2b
        'quy3_1',
        'quy3_2',
        'quy3_3',
        'tongsonguoi3', //mẫu 2b
        'quy1_tong',
        'quy2_tong',
        'quy3_tong',
        //Mẫu 2c tận dụng lại các trường theo tt cũ (lưu theo thông tư 50)
        'sotodanphobiengioi_2d',
        'sothon350hgd_2d',
        'sotodanpho500hgd_2d',
        'sochuyentuthon350hgd_2d',
        'sothonbiengioi_tong',
        'sotodanphobiengioi_tong',
        'sothon350hgd__tong',
        'sotodanpho500hgd_tong',
        'sothontrongdiem_tong',
        'sochuyentuthon350hgd_tong',
        'sothonconlai_tong',
        'sotoconlai_tong',
        'sothonbiengioi_2d',
        'sothontrongdiem_2d',
        'sothonconlai_2d',
        'sotoconlai_2d',
        //mẫu 2d tt50
        'soluongcanbo_2d',
        'hesoluongbq_2d',
        'hesophucapbq_2d',
        'tyledonggop_2d',
        'soluongdinhbien_2d',
        'quyluonggiam_2k',
        
        //mẫu 2đ
        'tongsonguoi2015',
        'tongsonguoi2017', //mẫu 2đ
        'quyluong', //mẫu 2đ
        'tongsodonvi1', //mẫu 2e
        'tongsodonvi2', //mẫu 2e
        'quy_tuchu', //mẫu 2e
        'nangcap_phucap', //trường để xác định xem đã tổng hợp phụ cấp ra bảng: nguonkinhphi_phucap
        //Mẫu 2đ
        'soluonghientai_2dd',
        'quyluonghientai_2dd',
        'kinhphitietkiem_2dd',
        'quyluongtietkiem_2dd',
        //Mẫu 2h
        'soluonghientai_2h',
        'hesoluong_2h',
        'hesophucap_2h',
        'tonghesophucapnd61_2h',
        'tonghesophucapqd244_2h',
        //Mẫu 2i
        'soluonghientai_2i',
        'hesoluong_2i',
        'hesophucap_2i',
        //Mẫu 2k
        'soluonggiam_2k',
        


        //Mẫu 4a
        'tinhgiam',
        'nghihuusom',
        'kpthuhut',
        'kpuudai',
        'tietkiem',
        'tinhgiambc_4a',
        'nghihuu_4a',
        'boiduong_4a',
        'satnhapdaumoi_4a',
        'thaydoicochetuchu_4a',
        'satnhapxa_4a',
        'tietkiem1', //trước 1 năm
        'tietkiem2', //trước 2 năm
        'thuchien1', //trước 1 năm
        'dutoan',
        'dutoan1', //trước 1 năm
        'huydongtx_hocphi_4a',
        'huydongtx_vienphi_4a',
        'huydongtx_khac_4a',
        'huydongktx_hocphi_4a',
        'huydongktx_vienphi_4a',
        'huydongktx_khac_4a',
        'kinhphigiamxa_4a',
        //Mẫu 2c 
        'soluongqt_2c',
        'sotienqt_2c',
        'soluongcanbo_2c',
        'hesoluong_2c',
        'phucapchucvu_2c',
        'phucapvuotkhung_2c',
        'phucaptnn_2c',
    ];
}
