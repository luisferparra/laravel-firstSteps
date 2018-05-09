<?php

namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use DB;
use Redis;

class preload_ageRange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:preload:ages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ages Ranges';

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
        $bar = $this->output->createProgressBar(DB::connection('segmentation')->table('age_range')->count());
       
        DB::connection('segmentation')->table('age_range')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
            $grouped = $records->groupBy('id_val');
            Redis::pipeline(function ($pipe) use ($grouped) {
                $arrArr = [];
                foreach ($grouped as $key => $val) {
                    if (!isset(${'arr' . $key}))
                        ${'arr' . $key} = [];
                    $arrArr[$key] = 1;
                    foreach ($val as $k => $v) {
                        # code...
                        ${'arr' . $key}[] = $v->id;
                    }
                    
                }
                foreach ($arrArr as $k => $v) {
                    $pipe->sadd('crm:segmentation:age_range_'.$k, ${'arr' . $k});
                    unset(${'arr' . $k});
                }
                unset($arrArr);
               
                

            });
            $bar->advance(count($records));
        });


        $bar->finish();
    }
}
