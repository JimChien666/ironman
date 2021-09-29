<?php

namespace Tests\Feature;

use App\Http\Controllers\api\UserController;
use Mockery;
use PDOException;
use Tests\TestCase;
use App\Models\User;
use Mockery\MockInterface;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertTrue;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test1()
    {
        new UserController();
        assertTrue(true);
    }

    public function invalidPasswordProvider()
    {
        // 密碼長度不足6位
        yield ["123"];
        // 密碼不含大寫字母
        yield ["123abc_"];
        // 密碼不含小寫字母
        yield ["123ABC_"];
        // 密碼不含數字
        yield ["ABCabc_"];
        // 密碼不含特殊符號
        yield ["ABCabcd"];
    }

    /**
     * @test
     * @testdox 測試delete方法，若delete成功，回傳success: true
     */
    public function destorySuccess()
    {
        //arrange
        $id = 10;
        $account = "JimChien";
        $password = "123Acb_";
        $name = "Jim";
        User::create([
            'id' => $id,
            'account' => $account,
            'password' => Hash::make($password),
            'name' => $name
        ]);
        // act&assert
        $this->deleteJson("/api/user/$id")
        ->assertStatus(200)
        ->assertJson([
            'success' => 'true'
        ]);
    }

    /**
     * @test
     * @testdox 測試delete方法，若delete失敗，回傳success: false
     */
    public function destoryFailed()
    {
        //arrange
        $id = 10;
        // act&assert
        $this->deleteJson("/api/user/$id")
        ->assertStatus(200)
        ->assertJson([
            'success' => 'false'
        ]);
    }
    /**
     * @test
     * @testdox 成功註冊一筆資料
     */
    public function storeSuccess()
    {
        //arrange
        $id = 10;
        $account = "JimChien";
        $password = "123Acb_";
        $username = "Jim";

        // act&assert
        $this->postJson("/api/user", [
            'id'=>$id,
            'account'=>$account,
            'password'=>$password,
            'username'=>$username
        ])
        ->assertStatus(200)
        ->assertJson([
            'success' => 'true'
        ]);
    }

    /**
     * @test
     * 
     */
    public function storeAccountRepeat()
    {
        //arrange
        $id = 10;
        $account = "JimChien";
        $password = "123Acb_";
        $username = "Jim";
        User::create([
            'id' => $id,
            'account' => $account,
            'password' => $password,
            'name' => $username
        ]);

        // act&assert
        $this->postJson("/api/user", [
            'id'=>$id,
            'account'=>$account,
            'password'=>$password,
            'username'=>$username
        ])
        ->assertStatus(200)
        ->assertJson([
            'success' => 'false',
            'error'=> '帳號重複註冊'
        ]);
    }

    /**
     * @test
     * @dataProvider invalidPasswordProvider
     */
    public function storeAccountPasseordInvalid(string $password)
    {
        //arrange
        $id = 10;
        $account = "JimChien";
        $username = "Jim";

        // act&assert
        $this->postJson("/api/user", [
            'id'=>$id,
            'account'=>$account,
            'password'=>$password,
            'username'=>$username
        ])
        ->assertStatus(200)
        ->assertJson([
            'success' => 'false',
            'error'=> '密碼需要6位數以上，並且至少包含大寫字母、小寫字母、數字、符號各一'
        ]);
    }

    /**
     * @test
     */
    public function storeGetPDOException()
    {
        //arrange
        $this->mock(UserService::class, function (MockInterface $mock) {
            $mock->shouldReceive('signUp')
                ->andThrow(new PDOException());
        });
        $id = 10;
        $account = "JimChien";
        $username = "Jim";
        $password = "123Acb_";

        // act&assert
        $this->postJson("/api/user", [
            'id'=>$id,
            'account'=>$account,
            'password'=>$password,
            'username'=>$username
        ])
        ->assertStatus(200)
        ->assertJson([
            'success' => 'false',
            'error'=> 'DB error'
        ]);
    }
}
