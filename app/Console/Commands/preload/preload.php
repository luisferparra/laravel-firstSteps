<?php

namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use DB;
use Redis;

class preload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:preload:openers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba comando de insertar en Redis los valores de Openers';

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
        $bar = $this->output->createProgressBar(DB::connection('segmentation')->table('marketing_openers')->count());
        
        DB::connection('segmentation')->table('marketing_openers')->orderBy('id')->chunk(10000, function($records) use (&$bar){
            Redis::pipeline(function ($pipe) use ($records) {
                $arrIns = [0=>[],1=>[]];
                foreach ($records as $key => $val) {
                    $arrIns[$val->id_val][] = $val->id;               
                }
                if (!empty($arrIns[0]))
                $pipe->sadd('crm:segmentation:openers_0', $arrIns[0]);
                if (!empty($arrIns[1]))
                $pipe->sadd('crm:segmentation:openers_1', $arrIns[1]);
                unset($arrIns);
                
            });
            $bar->advance(count($records));
        });


        $bar->finish();
    }
}
