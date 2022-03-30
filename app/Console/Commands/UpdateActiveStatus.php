<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

// Second attempt, pulled data from the database and converted to strtotime and tested that against the current day subtract 2 years.
// Could be modified to further to change the value of socks to a parameter passed into the function so it becomes dynamic so any product_type could be passed in.

// New note - expiry function is now dynamic.

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
        //$this->insertProduct();
        $this->itemExpiryCheck("socks");
    }

    public function insertProduct()
    {
        $testDate = Date("2022-01-10");

        DB::table("products")->insert(['product_type' => 'socks', 'quantity' => 3, 'active' => true, 'created_at' => $testDate]);
        DB::table("products")->insert(['product_type' => 'shoes', 'quantity' => 5, 'active' => true, 'created_at' => $testDate]);

    }

    public function itemExpiryCheck(String $item)
    {
        $testDate = strtotime('2022-04-01 -2 year');

        $date = DB::table("products")->select("created_at")->where("product_type", "=", $item)->get();

        $itemCreatedDate = strtotime($date[0]->created_at);

        if ($itemCreatedDate > $testDate) {
            DB::table("products")
                ->where('product_type', '=', $item)
                ->update(["active" => false]);

            echo $item . " Database updated";
        }
        else {
            echo "All items within two-year range";
        }
    }
}
