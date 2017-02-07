<?php

namespace App\Console\Commands;

use App\Repositories\ProxyRepository;
use App\Proxy;
use DB;
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

        $this->line('Updating proxies...');

        $proxies = file_get_contents("http://account.fineproxy.org/api/getproxy/?format=txt&type=httpip&login={$login}&password={$password}");

        if (empty($proxies)) {
            $this->warn('No proxies at FineProxy.org');
            return;
        }

        $arr = explode("\n", $proxies);
        $this->info(count($arr) . " proxies found");

        if (!empty($arr)) {
            // remove old proxies
            DB::table('proxies')->delete();

            foreach ($arr as $proxy) {
                if (empty($proxy)) continue;

                // divide to ip and port
                $proxyArr = explode(":", $proxy);

                ProxyRepository::create([
                    'ip' => $proxyArr[0],
                    'port' => $proxyArr[1]
                ]);

                $this->line("Proxy {$proxyArr[0]} added");
            }
        }
    }
}
