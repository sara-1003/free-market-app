<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_「購入する」ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
            'profile_img' => 'profiles/default.png',
        ]);

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'profile_img' => 'profiles/default.png',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
        ]);

        $response = $this->get('/?tab=best');

        $response->assertSee('SOLD');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold' => true,
        ]);
    }


    public function test_「プロフィールの購入した商品一覧」に追加されている()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済みテスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'profile_img' => 'profiles/default.png',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
        ]);

        $response = $this->get('/mypage?page=buy');

        $response->assertSee('購入済みテスト商品');
    }

    public function test_小計画面で変更が反映される()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '支払い方法テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'profile_img' => 'profiles/default.png',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ]);
    }

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '住所反映テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);


        $user->profile()->create([
            'profile_img' => 'profiles/default.png',
            'post_code' => '111-1111',
            'address' => '東京都',
            'building' => '東京マンション',
        ]);

        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '222-2222',
            'address' => '静岡県',
            'building' => '静岡マンション',
        ]);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('222-2222');
        $response->assertSee('静岡県');
        $response->assertSee('静岡マンション');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '住所テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);


        $this->actingAs($user);


        $user->profile()->create([
            'profile_img' => 'profiles/default.png',
            'post_code' => '111-1111',
            'address' => '東京都',
            'building' => '東京マンション',
        ]);


        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '222-2222',
            'address' => '静岡県',
            'building' => '静岡マンション',
        ]);

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id'   => $user->id,
            'item_id'   => $item->id,
            'post_code' => '222-2222',
            'address'   => '静岡県',
            'building'  => '静岡マンション',
        ]);
    }
}
