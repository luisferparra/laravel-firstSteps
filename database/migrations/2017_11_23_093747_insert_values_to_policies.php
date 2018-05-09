<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertValuesToPolicies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('policies')->insert([['policy'=>'Política Uno','active'=>1,'created_at'=>DB::raw('now()'),'id'=>1],
            ['policy'=>'Política Dos','active'=>1,'created_at'=>DB::raw('now()'),'id'=>2],
            ['policy'=>'Política Tres','active'=>0,'created_at'=>DB::raw('now()'),'id'=>3]]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::table('policies')->where('id','<=',3)->delete(); 
    }
}
