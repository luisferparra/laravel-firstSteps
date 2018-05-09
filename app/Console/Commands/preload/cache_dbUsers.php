<?php

/**
 * Funcionalidad que introduce en caché a todos los usuarios en un array clave=>valor
 */

namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use DB;

class cache_dbUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:crm:dbUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Introduce into caché with key "users" all the DB';

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
        ini_set('memory_limit', '8G');
        set_time_limit(0);

        if (!Cache::has('usrsAll')) {
            $arrData = [];
            $bar = $this->output->createProgressBar(5033063);
            $q = "select id,group_concat(id_val SEPARATOR ',') as bbdds from bbdd_users GROUP BY id";
            $db = DB::connection('segmentation')->getPdo();
            $query = $db->prepare($q);
            $query->execute();
            $arrBbdds = [];
            $cont = 0;
            while ($item = $query->fetch()) {
                // process data
                //dd($item);
                $id = $item['id'];
                $bbddList = $item['bbdds'];
                $bbdd = explode(',', $bbddList);
                $arrData[$id] = array_flip($bbdd);
                foreach ($bbdd as $key => $bbddItem) {
                    if (!array_key_exists($bbddItem, $arrBbdds)) {
                        ${'bbdd_' . $bbddItem} = [];
                        $arrBbdds[$bbddItem] = 1;
                    }
                    ${'bbdd_' . $bbddItem}[$id] = 1;
                }
                $cont++;
                if ($cont >= 10000) {
                    $bar->advance($cont);
                    $cont = 0;
                }



            }
            Cache::put('usrsAll', $arrData, 300);
            foreach ($arrBbdds as $idBbdd => $garbage) {
                Cache::put('bbdd_' . $idBbdd, ${'bbdd_' . $idBbdd}, 300);
            }
            $bar->finish();
        }
        $arrBbdd = DB::connection('segmentation')->table('bbdd_lists')->where('active', true)->select('id')->get();
        $arrBbDdCache = [];
        foreach ($arrBbdd as $key => $bbdd) {
            $arrBbDdCache[$bbdd->id] = 1;
        }
        Cache::add('bbddList',$arrBbDdCache,300);


    }
}
