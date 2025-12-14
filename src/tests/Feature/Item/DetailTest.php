<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;

class detailTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が表示される()
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'カテゴリA',
        ]);

        $item = Item::create([
            'name' => 'テスト商品A',
            'brand' => 'ブランドA',
            'description' => '商品説明A',
            'price' => 1500,
            'condition' => '新品',
            'img' => 'items/a.jpg',
            'user_id' => $user->id,
            'sold' => false,
        ]);

        $item->category()->attach($category->id);

        $commentUser = User::factory()->create();
        $comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
            'comment' => 'コメント内容A',
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee($item->description);
        $response->assertSee('¥1,500');
        $response->assertSee($item->condition);
        $response->assertSee($category->name);
        $response->assertSee($comment->comment);
        $response->assertSee($commentUser->name);
        $response->assertSee('storage/items/a.jpg');
        $response->assertSee((string)$item->favorite->count()); $response->assertSee((string)$item->comment->count());
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        $user = User::factory()->create();

        $category1 = Category::create(['name' => 'カテゴリA']);
        $category2 = Category::create(['name' => 'カテゴリB']);

        $item = Item::create([
            'name' => 'テスト商品A',
            'brand' => 'ブランドA',
            'description' => '商品説明A',
            'price' => 1500,
            'condition' => '新品',
            'img' => 'items/a.jpg',
            'user_id' => $user->id,
            'sold' => false,
        ]);

        $item->category()->attach([$category1->id, $category2->id]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }
}
