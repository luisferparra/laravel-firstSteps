<?php
/**
 * Comando que cargará la tabla bbdd_users en redis
 */
namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use DB;
use Redis;

class preload_databases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:preload:databases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Users Data Base into Redis. Generates Sets of users by DB. Name of the sets: crm:db:x';

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
        $cont = DB::connection('segmentation')->table('bbdd_users')->count();
        $bar = $this->output->createProgressBar($cont);
        DB::connection('segmentation')->table('bbdd_users')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
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
                    $pipe->sadd('crm:db:'.$k, ${'arr' . $k});
                    unset(${'arr' . $k});
                }
                unset($arrArr);
               
                

            });
            $bar->advance(count($records));
        });
        $bar->finish();
        
        Redis::save();
    }
}
