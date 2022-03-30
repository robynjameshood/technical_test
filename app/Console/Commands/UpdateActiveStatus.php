<?php

namespace App\Console\Commands;

use DateTime;
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
        $this->insertProduct();
        $this->itemExpiryCheck("socks");
    }

    public function insertProduct()
    {
        $testDate = Date("2022-04-01");

        DB::table("products")->insert(['product_type' => 'socks', 'quantity' => 3, 'active' => true, 'created_at' => $testDate]);
        DB::table("products")->insert(['product_type' => 'shoes', 'quantity' => 5, 'active' => true, 'created_at' => $testDate]);

    }

    public function itemExpiryCheck(String $item)
    {
        $today = Date("2020-03-29");

        $date2 = DB::table("products")->select("created_at")->where("product_type", "=", $item)->get();

        $itemCreatedDate = $date2[0]->created_at; // value in unix of the date the item was inserted into the database.

        $dateTimeOne = new DateTime($today);
        $dateTimeTwo = new DateTime($itemCreatedDate);
        $difference = $dateTimeTwo->diff($dateTimeOne);

        if ($difference->y >= 2 && $difference->m >= 0 && $difference->d > 0) {
            DB::table("products")
                ->where('product_type', '=', $item)
                ->update(["active" => false]);

            echo $item . " Database updated";
        } else {
            echo "All items within two-year range";
        }
    }
}
