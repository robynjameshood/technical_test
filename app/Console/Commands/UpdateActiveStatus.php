<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateActiveStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates status of products in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->insertProduct();
        $this->itemExpiryCheck();
    }

    public function insertProduct() {
        DB::table("products")->insert(['product_type' => 'socks', 'quantity' => 3, 'yearsActive' =>2, 'active' =>true]);
    }

    public function itemExpiryCheck() {
        DB::table("products")
            ->where("yearsActive", '>=', 2)
            ->where('product_type', '>=', "socks")
            ->update(["active" => false]);
    }
}
