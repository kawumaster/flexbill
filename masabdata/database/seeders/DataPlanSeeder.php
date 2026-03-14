<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataPlan;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //
        DataPlan::insert([
    ['network' => 'MTN', 'plan' => '1GB', 'price' => 600],
    ['network' => 'AIRTEL', 'plan' => '2GB', 'price' => 700],
    ['network' => 'GLO', 'plan' => '1.5GB', 'price' => 500],
]);
    }
}
