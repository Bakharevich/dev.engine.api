<?php

namespace App\Console\Commands;

use App\Repositories\ProxyRepository;
use Illuminate\Console\Command;

class UpdateProxy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxy:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the proxies from FineProxy';

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
     * @return mixed
     */
    public function handle()
    {
        $login    = getenv('FINEPROXY_LOGIN');
        $password = getenv('FINEPROXY_PASSWORD');

        $proxies = file_get_contents("http://account.fineproxy.org/api/getproxy/?format=txt&type=httpip&login={$login}&password={$password}");

        $arr = explode("\n", $proxies);

        if (!empty($arr)) {
            foreach ($arr as $proxy) {
                if (empty($proxy)) continue;

                // divide to ip and port
                $proxyArr = explode(":", $proxy);

                ProxyRepository::create([
                    'ip' => $proxyArr[0],
                    'port' => $proxyArr[1]
                ]);
            }
        }
    }
}
