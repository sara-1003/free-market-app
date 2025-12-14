<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/login',[
            'email'=>'',
            'password'=>'12345678',
        ]);

        $response->assertSessionHasErrors(['email'=>'メールアドレスを入力してください']);
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/login',[
            'email'=>'test@example.com',
            'password'=>'',
        ]);

        $response->assertSessionHasErrors(['password'=>'パスワードを入力してください']);
    }

    public function test_入力情報が間違っている場合、バリデーションメッセージが表示される()
    {
        User::factory()->create([
            'email'=>'test@example.com',
            'password'=>bcrypt('12345678'),
        ]);

        $response=$this->post('/login',[
            'email'=>'jest@example.com',
            'password'=>'11111111',
        ]);


        $response->assertSessionHasErrors(['email'=>'ログイン情報が登録されていません']);
    }

    public function test_正しい情報が入力された場合、ログイン処理が実行される()
    {
        $user=User::factory()->create([
            'email'=>'test@example.com',
            'password'=>bcrypt('12345678'),
        ]);

        $response=$this->post('/login',[
            'email'=>'test@example.com',
            'password'=>'12345678',
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertAuthenticatedAs($user);
    }
}
