<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が入力されていない場合バリデーションエラーになる()
    {
        $response=$this->post('/register',[
            'name'=>'',
            'email'=>'test@example.com',
            'password'=>'12345678',
            'password_confirmation'=>'12345678',
        ]);

        $response->assertSessionHasErrors(['name'=>'お名前を入力してください']);
    }

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/register',[
            'name'=>'太郎',
            'email'=>'',
            'password'=>'12345678',
            'password_confirmation'=>'12345678',
        ]);

        $response->assertSessionHasErrors(['email'=>'メールアドレスを入力してください']);
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/register',[
            'name'=>'太郎',
            'email'=>'test@example.com',
            'password'=>'',
            'password_confirmation'=>'12345678',
        ]);

        $response->assertSessionHasErrors(['password'=>'パスワードを入力してください']);
    }

    public function test_パスワードが7文字以下の場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/register',[
            'name'=>'太郎',
            'email'=>'test@example.com',
            'password'=>'1234567',
            'password_confirmation'=>'12345678',
        ]);

        $response->assertSessionHasErrors(['password'=>'パスワードは8文字以上で入力してください']);
    }

    public function test_パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される()
    {
        $response=$this->post('/register',[
            'name'=>'太郎',
            'email'=>'test@example.com',
            'password'=>'12345678',
            'password_confirmation'=>'12345679',
        ]);

        $response->assertSessionHasErrors(['password_confirmation'=>'パスワードと一致しません']);
    }

    public function test_全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される()
    {
        $response=$this->post('/register',[
            'name'=>'太郎',
            'email'=>'test@example.com',
            'password'=>'12345678',
            'password_confirmation'=>'12345678',
        ]);

        $this->assertDatabaseHas('users',['email'=>'test@example.com']);
        $response->assertRedirect('/profile');
    }

    public function test_会員登録後、認証メールが送信される()
    {
        \Illuminate\Support\Facades\Notification::fake();
        $this->post('/register', [
            'name' => '太郎',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = \App\Models\User::where('email', 'test@example.com')->first();

        \Illuminate\Support\Facades\Notification::assertSentTo(
        [$user],
        \Illuminate\Auth\Notifications\VerifyEmail::class
        );
    }

    public function test_メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee(route('verification.external'));

        $redirect = $this->get(route('verification.external'));
        $redirect->assertRedirect('http://localhost:8025');
    }

    public function test_メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('profile.create'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
