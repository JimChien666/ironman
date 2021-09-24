<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\PasswordInvalidException;
use Exception;

class SignUp extends Command
{
    const PASSWORD_REGEX = "/^(?=.*[^a-zA-Z0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/";
    /**
     * 註冊需要輸入帳號跟密碼還有名稱
     *
     * @var string
     */
    protected $signature = 'sign-up {account} {password} {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '註冊用的指令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(UserService $userService)
    {
        $account = $this->argument('account');
        $password = $this->argument('password');
        $username = $this->argument('username');
        try{
            if($userService->signUp($account, $password, $username)){
                $this->line("註冊成功");
            }
        } catch (Exception $e){
            $this->error($e->getMessage());
        }
        
        return 0;
    }
}
