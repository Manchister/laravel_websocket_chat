<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;
use App\Http\Controllers\WebSocketController;

class WebSocketSecureServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocketsecure:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $loop   = Factory::create();
        $webSock = new SecureServer(
            new Server('0.0.0.0:8091', $loop),
            $loop,
            array(
                'local_cert'        => '/ssl/certs/joyane_store_cb6e5_bb47b_1593302399_80af7847dd2a3e2eb66bea203e9e8cb2.crt', // path to your cert
                'local_pk'          => '/ssl/keys/cb6e5_bb47b_520988c65638c5537a457f337d9447af.key', // path to your server private key
                'allow_self_signed' => TRUE, // Allow self signed certs (should be false in production)
                'verify_peer' => FALSE
            )
        );
        // Ratchet magic
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WebSocketController()
                )
            ),
            $webSock
        );
        $loop->run();
    }
}
