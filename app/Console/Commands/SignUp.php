<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    public function handle()
    {
        $account = $this->argument('account');
        $password = $this->argument('password');
        $username = $this->argument('username');
        $validator = Validator::make(['password' => $password], [
            'password' => 'regex:' . SignUp::PASSWORD_REGEX
        ]);

        if ($validator->fails()) {
            $this->error("密碼需要6位數以上，並且至少包含大寫字母、小寫字母、數字、符號各一");
            return 1;
        }
        $user = User::where('account', $account)->first();
        // $user = DB::table('users')->where('account', $account)->first();
        if($user !== null){
            $this->error("帳號重複註冊");
            return 1;
        }
        User::create([
            'account' => $account,
            'password' => Hash::make($password),
            'name' => $username
        ]);
        // DB::table('users')->insert([
        //         'account' => $account,
        //         'password' => Hash::make($password),
        //         'name' => $username
        //     ]);
        $this->line("帳號註冊成功");
        return 0;
    }
}
