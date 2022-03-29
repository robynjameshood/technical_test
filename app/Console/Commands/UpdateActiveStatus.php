<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
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

    public function insertProduct()
    {
        $testDate = Date("2020-01-10");

        DB::table("products")->insert(['product_type' => 'socks', 'quantity' => 3, 'active' => true, 'created_at' => $testDate]);
        DB::table("products")->insert(['product_type' => 'shoes', 'quantity' => 5, 'active' => true, 'created_at' => $testDate]);

    }

    public function itemExpiryCheck()
    {
        $testDate = strtotime('2010-01-01 -2 year');

        $date = DB::table("products")->select("created_at")->where("product_type", "=", "socks")->get();

        $itemCreatedDate = strtotime($date[0]->created_at);

        if ($itemCreatedDate > $testDate) {
            DB::table("products")
                ->where('product_type', '=', "socks")
                ->update(["active" => false]);

            echo "Database updated";
        }
        else {
            echo "All items within two-year range";
        }
    }
}
