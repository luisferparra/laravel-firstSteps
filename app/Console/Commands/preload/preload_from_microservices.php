<?php

namespace App\Console\Commands\preload;

use Illuminate\Console\Command;
use DB;
use Redis;

class preload_from_microservices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:preload:microservicesDB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga datos de la BBDD crm_microservices SEGM_PUBLISHERS, USERS_LOCATION y SEGMGTP_SURVEY_ANSWERS';

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
        $contPubs = DB::connection('microservices')->table('SEGM_PUBLISHERS')->count();
        $this->info('Contador Publihsers: '.$contPubs);
        $contLocations = DB::connection('microservices')->table('USERS_LOCATION')->count();
        $this->info('Contador Users Locations: '.$contLocations);
        $contAnswers = DB::connection('microservices')->table('SEGMGTP_SURVEY_ANSWER')->count();
        $this->info('Contador GTP Answers: '.$contAnswers);
        $bar = $this->output->createProgressBar($contPubs + $contLocations + $contAnswers);


        $this->warn('Empezamos Publishers');

        DB::connection('microservices')->table('SEGM_PUBLISHERS')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
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
                    $pipe->sadd('crm:segmentation:publishers_'.$k, ${'arr' . $k});
                    unset(${'arr' . $k});
                }
                unset($arrArr);
               
                

            });
            $bar->advance(count($records));
        });



        $this->info('Terminado Pubs');


        $this->warn('Empezamos Locations');

        DB::connection('microservices')->table('USERS_LOCATION')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
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
                    $pipe->sadd('crm:segmentation:locations_'.$k, ${'arr' . $k});
                    unset(${'arr' . $k});
                }
                unset($arrArr);
               
                

            });
            $bar->advance(count($records));
        });



        $this->info('Terminado Locations');
        $this->warn('Empezamos GTP');

        DB::connection('microservices')->table('SEGMGTP_SURVEY_ANSWER')->orderBy('id')->chunk(10000, function ($records) use (&$bar) {
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
                    $pipe->sadd('crm:gtp:surve_answers_'.$k, ${'arr' . $k});
                    unset(${'arr' . $k});
                }
                unset($arrArr);
               
                

            });
            $bar->advance(count($records));
        });



        $this->info('Terminado Locations');

        $bar->finish();
        $this->info('Terminado');
    }
}
