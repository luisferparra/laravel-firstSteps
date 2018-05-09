<?php

namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use DB;
use Redis;

class preload_clickers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:preload:clickers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clickers n Purchasers';

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
        $bar = $this->output->createProgressBar(DB::connection('segmentation')->table('marketing_clickers')->count() + DB::connection('segmentation')->table('marketing_purchasers')->count());
        
        DB::connection('segmentation')->table('marketing_clickers')->orderBy('id')->chunk(10000, function($records) use (&$bar){
            Redis::pipeline(function ($pipe) use ($records) {
                $arrIns = [0=>[],1=>[]];
                foreach ($records as $key => $val) {
                    $arrIns[$val->id_val][] = $val->id;               
                }
                if (!empty($arrIns[0]))
                $pipe->sadd('crm:segmentation:clickers_0', $arrIns[0]);
                if (!empty($arrIns[1]))
                $pipe->sadd('crm:segmentation:clickers_1', $arrIns[1]);
                unset($arrIns);
                
            });
            $bar->advance(count($records));
        }); 
        $this->info('Ya terminado clickers');
        DB::connection('segmentation')->table('marketing_purchasers')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
            Redis::pipeline(function ($pipe) use ($records) {
                $arrIns = [0 => [], 1 => []];
                foreach ($records as $key => $val) {
                    $arrIns[$val->id_val][] = $val->id;
                }
                if (!empty($arrIns[0]))
                    $pipe->sadd('crm:segmentation:purchasers_0', $arrIns[0]);
                if (!empty($arrIns[1]))
                    $pipe->sadd('crm:segmentation:purchasers_1', $arrIns[1]);
                unset($arrIns);

            });
            $bar->advance(count($records));
        });
        $bar->finish();
    }
}
