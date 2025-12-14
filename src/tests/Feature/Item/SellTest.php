<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面にて必要な情報が保存できること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::create([
            'name' => '家電',
        ]);

        $this->get('/sell')->assertStatus(200);

        $response = $this->post('/sell', [
            'category_ids' => [$category->id],
            'condition'   => '新品',
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'テスト商品の説明です',
            'price'       => 5000,
            'img' => UploadedFile::fake()->create(
                'test.jpg',
                100,
                'image/jpeg'
            ),
        ]);

        $response->assertStatus(302);

        $item = \App\Models\Item::first();

        $this->assertDatabaseHas('items', [
            'user_id'     => $user->id,
            'condition'   => '新品',
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'テスト商品の説明です',
            'price'       => 5000,
        ]);

        $this->assertDatabaseHas('category_items', [
            'category_id' => $category->id,
            'item_id'     => $item->id,
        ]);
        Storage::disk('public')->assertExists($item->img);
    }
}
