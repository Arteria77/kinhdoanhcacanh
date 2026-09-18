<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cá Koi Nhật',
                'description' => 'Các dòng cá Koi thuần chủng Kohaku, Sanke, Showa nhập khẩu và F1 tuyển chọn.',
            ],
            [
                'name' => 'Cá Rồng',
                'description' => 'Cá Rồng Huyết Long, Kim Long Quá Bối, Kim Long Hồng Vĩ quyền quý, phong thủy.',
            ],
            [
                'name' => 'Cá Betta Cảnh',
                'description' => 'Betta Halfmoon, Dumbo, Fancy, Koi Galaxy màu sắc rực rỡ, đuôi xòe tuyệt đẹp.',
            ],
            [
                'name' => 'Cá Guppy (Bảy Màu)',
                'description' => 'Guppy Full Gold, Dumbo Red Tail, Blue Grass thuần chủng bơi theo đàn sinh động.',
            ],
            [
                'name' => 'Cá Đĩa (Discus)',
                'description' => 'Nhất đại mỹ ngư cá Đĩa bồ câu, xanh lam, đỏ cẩm thạch kiêu sa trong bể kính.',
            ],
            [
                'name' => 'Cá La Hán',
                'description' => 'Cá La Hán gù to, châu sáng, vảy chữ kim hoa may mắn tài lộc cho gia chủ.',
            ],
            [
                'name' => 'Cá Vàng (Goldfish)',
                'description' => 'Cá Ranchu, Oranda đầu lân, Ryukin gù đuôi quạt ngộ nghĩnh, hiền lành.',
            ],
            [
                'name' => 'Tép Cảnh & Thủy Sinh',
                'description' => 'Các loài cá thủy sinh bầy đàn như Neon, Tam Giác, Sọc Ngựa và Tép cảnh nhiều màu.',
            ],
        ];

        foreach ($categories as $catData) {
            $cat = Category::firstOrCreate(
                ['name' => $catData['name']],
                [
                    'slug' => Str::slug($catData['name']),
                    'description' => $catData['description'],
                ]
            );
        }

        // Gán danh mục mặc định cho các sản phẩm chưa có danh mục
        $firstCat = Category::first();
        if ($firstCat) {
            Product::whereNull('category_id')->update(['category_id' => $firstCat->id]);
        }
    }
}
