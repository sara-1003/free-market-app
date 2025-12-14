<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class CommentTest extends TestCase
{
    public function test_ログイン済みのユーザーはコメントを送信できる()
    {

        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $this->assertEquals(0, $item->comment()->count());

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'テスト',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テスト',
        ]);

        $item->refresh();

        $this->assertEquals(1, $item->comment()->count());

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('テスト');
        $response->assertSee('コメント(1)');
        $response->assertSee($user->name);
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $owner = User::factory()->create();

        $item = Item::create([
            'user_id' => $owner->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'ゲストコメント',
        ]);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $response = $this->from("/item/{$item->id}")
            ->post("/item/{$item->id}/comment", [
                'comment' => '',
            ]);

        $response->assertRedirect("/item/{$item->id}");

        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
        ]);
    }
    
    public function test_コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->from("/item/{$item->id}")
            ->post("/item/{$item->id}/comment", [
                'comment' => $longComment,
            ]);

        $response->assertRedirect("/item/{$item->id}");

        $response->assertSessionHasErrors([
            'comment' => '255文字以下で入力してください',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => $longComment,
        ]);
    }
}
