<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmquanhegd extends Model
{
    protected $table = 'dmquanhegd';
    protected $fillable = [
        'id',
        'quanhe',
        'nhom',
        'sapxep'
    ];
    /*
     INSERT INTO `dmquanhegd` (`id`, `quanhe`, `nhom`, `sapxep`, `created_at`, `updated_at`) VALUES
(1, 'Ông nội', 2, 1, NULL, NULL),
(2, 'Ông ngoại', 2, 1, NULL, NULL),
(3, 'Ông nội vợ', 3, 1, NULL, NULL),
(4, 'Ông ngoại vợ', 3, 1, NULL, NULL),
(5, 'Ông nội chồng', 3, 1, NULL, NULL),
(6, 'Ông ngoại chồng', 3, 1, NULL, NULL),
(7, 'Bà nội', 2, 2, NULL, NULL),
(8, 'Bà ngoại', 2, 2, NULL, NULL),
(9, 'Bà nội vợ', 3, 2, NULL, NULL),
(10, 'Bà ngoại vợ', 3, 2, NULL, NULL),
(11, 'Cha đẻ', 2, 3, NULL, NULL),
(12, 'Mẹ đẻ', 2, 3, NULL, NULL),
(13, 'Cha vợ', 3, 3, NULL, NULL),
(14, 'Cha chồng', 3, 3, NULL, NULL),
(15, 'Mẹ vợ', 3, 3, NULL, NULL),
(16, 'Mẹ chồng', 3, 3, NULL, NULL),
(17, 'Cha kế', 2, 3, NULL, NULL),
(18, 'Mẹ kế', 2, 3, NULL, NULL),
(19, 'Cha nuôi', 2, 3, NULL, NULL),
(20, 'Mẹ nuôi', 2, 3, NULL, NULL),
(21, 'Bác', 2, 4, NULL, NULL),
(22, 'Chú', 2, 4, NULL, NULL),
(23, 'Cô', 2, 4, NULL, NULL),
(24, 'Cậu', 2, 4, NULL, NULL),
(25, 'Dì', 2, 4, NULL, NULL),
(26, 'Anh ruột', 2, 5, NULL, NULL),
(27, 'Chị ruột', 2, 5, NULL, NULL),
(28, 'Em ruột', 2, 5, NULL, NULL),
(29, 'Anh vợ', 3, 5, NULL, NULL),
(30, 'Chị vợ', 3, 5, NULL, NULL),
(31, 'Em vợ', 3, 5, NULL, NULL),
(32, 'Anh chồng', 3, 5, NULL, NULL),
(33, 'Chị chồng', 3, 5, NULL, NULL),
(34, 'Em chồng', 3, 5, NULL, NULL),
(35, 'Vợ', 1, 6, NULL, NULL),
(36, 'Chồng', 1, 6, NULL, NULL),
(37, 'Con', 1, 7, NULL, NULL),
(38, 'Con nuôi', 1, 7, NULL, NULL),
(39, 'Cha vợ kế', 3, 1, NULL, NULL),
(40, 'Bà nội chồng', 1, 99, NULL, NULL),
(41, 'Bà ngoại chồng', 1, 99, NULL, NULL),
(42, 'bác vợ', 1, 99, NULL, NULL),
(43, 'cô vợ', 1, 99, NULL, NULL),
(44, 'chú vợ', 1, 99, NULL, NULL),
(45, 'dì vợ', 1, 99, NULL, NULL),
(46, 'cậu vợ', 1, 99, NULL, NULL);

     */
}
