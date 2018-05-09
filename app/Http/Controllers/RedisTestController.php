<?php

/**
 * Test Controller de REdis
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Redis;
use DB;

class RedisTestController extends Controller
{
    /**
     * Función de testeo de traer datos de redis y procesarlos. a ver cómo lo hacemos
     * Vamos a hacer un Union de valores por segmentación (or) y luego un sinter de todos los que haya
     *
     * @return void
     */
    public function redisTest()
    {

        ini_set('memory_limit', '4G');
        set_time_limit(0);
        $s1 = microtime(true);
        $start = $s1;
        Redis::sunionstore('temp:pubs', [
            'crm:segmentation:publishers_821',
            'crm:segmentation:publishers_109',
            'crm:segmentation:publishers_15',
            'crm:segmentation:publishers_108',
            'crm:segmentation:publishers_52',
            'crm:segmentation:publishers_65',
            'crm:segmentation:publishers_33',
            'crm:segmentation:publishers_805',
            'crm:segmentation:publishers_161',
            'crm:segmentation:publishers_250',
            'crm:segmentation:publishers_200'
        ]);
        $s2 = microtime(true);
        dump('Cargado Pubs en ' . ($s2 - $s1));
        $s1 = microtime(true);
        Redis::sunionstore('temp:locations', [
            'crm:segmentation:locations_5',
            'crm:segmentation:locations_8',
            'crm:segmentation:locations_28',
            'crm:segmentation:locations_31',
            'crm:segmentation:locations_46',
            'crm:segmentation:locations_50'
        ]);
        $s2 = microtime(true);
        dump('Cargado Locagtions en ' . ($s2 - $s1));
        $s1 = microtime(true);

        Redis::sunionstore('temp:gtp', [
            'crm:gtp:surve_answers_111',
            'crm:gtp:surve_answers_110',
            'crm:gtp:surve_answers_4361',
            'crm:gtp:surve_answers_33097',
            'crm:gtp:surve_answers_4310',
            'crm:gtp:surve_answers_4315',
            'crm:gtp:surve_answers_49',
            'crm:gtp:surve_answers_4322',
            'crm:gtp:surve_answers_16663',
            'crm:gtp:surve_answers_16667',
            'crm:gtp:surve_answers_41227'
        ]);
        $s2 = microtime(true);
        dump('Cargado gtp en ' . ($s2 - $s1));
        $s1 = microtime(true);
        $resp = Redis::sinter(['temp:pubs', 'temp:locations', 'temp:gtp']);
        $s2 = microtime(true);
        dump('Cargado datos en ' . ($s2 - $s1));
        dump('Cont: ' . count($resp));
        Redis::del(['temp:pubs', 'temp:locations', 'temp:gtp']);
        $q = "SELECT id,bbdd_subscribed FROM bbdd_subscribers WHERE id IN (" . implode(',', $resp) . ")";
        $db = DB::connection('microservices')->getPdo();
        $query = $db->prepare($q);
        $query->execute();
        $arrFinal = array();
        while ($item = $query->fetch()) {
			// process data
			//dd($item);
            $arrFinal[$item['id']] = $item['bbdd_subscribed'];

        }
        dump('Total: ' . (microtime(true) - $start));
        $time = microtime(true) - $start;

        dump('Cont: ' . count($resp) . ' y traidos: ' . count($arrFinal));
        $items = implode(',', $resp);
        dump('ALL: ' . ($time));



        dd('final 1.77segundos en total');
    }


    /**
     * Función de testeo de traer datos de redis y procesarlos. a ver cómo lo hacemos
     * Vamos a hacer un Union de valores por segmentación (or) y luego un sinter de todos los que haya
     * La diferencia con la anterior es que en la anterior los datos finales están cargados en MySql y aquí TODOS los datos están en Redis. Los datos por bbdd están en crm:bbdd:x donde x es la bbdd
     * esta prueba solo contará el resultado
     *
     * @return void
     */
    public function redisTestAllRedis()
    {

        ini_set('memory_limit', '4G');
        set_time_limit(0);
        $s1 = microtime(true);
        $arrSinters = [];
        $start = $s1;
        Redis::sunionstore('temp:pubs', [
            'crm:segmentation:publishers_821',
            'crm:segmentation:publishers_109',
            'crm:segmentation:publishers_15',
            'crm:segmentation:publishers_108',
            'crm:segmentation:publishers_52',
            'crm:segmentation:publishers_65',
            'crm:segmentation:publishers_33',
            'crm:segmentation:publishers_805',
            'crm:segmentation:publishers_161',
            'crm:segmentation:publishers_250',
            'crm:segmentation:publishers_200'
        ]);
        $arrSinters[] = 'temp:pubs';
        $s2 = microtime(true);
        dump('Cargado Pubs en ' . ($s2 - $s1));
        $s1 = microtime(true);
        Redis::sunionstore('temp:locations', [
            'crm:segmentation:locations_5',
            'crm:segmentation:locations_8',
            'crm:segmentation:locations_28',
            'crm:segmentation:locations_31',
            'crm:segmentation:locations_46',
            'crm:segmentation:locations_50'
        ]);
        $s2 = microtime(true);
        $arrSinters[] = 'temp:locations';

        dump('Cargado Locagtions en ' . ($s2 - $s1));
        $s1 = microtime(true);

        Redis::sunionstore('temp:gtp', [
            'crm:gtp:surve_answers_111',
            'crm:gtp:surve_answers_110',
            'crm:gtp:surve_answers_4361',
            'crm:gtp:surve_answers_33097',
            'crm:gtp:surve_answers_4310',
            'crm:gtp:surve_answers_4315',
            'crm:gtp:surve_answers_49',
            'crm:gtp:surve_answers_4322',
            'crm:gtp:surve_answers_16663',
            'crm:gtp:surve_answers_16667',
            'crm:gtp:surve_answers_41227'
        ]);
        $s2 = microtime(true);
        $arrSinters[] = 'temp:gtp';

        dump('Cargado gtp en ' . ($s2 - $s1));
        $s1 = microtime(true);
        Redis::sinterstore('temp:sinter', ['temp:pubs', 'temp:locations', 'temp:gtp']);
        $numElementsTotal = Redis::scard('temp:sinter');
        dump('Num Elements Total: ' . $numElementsTotal);
        dump('Total Elements Time: ' . (microtime(true) - $s1));
        $arrSinters[] = 'temp:sinter';
            //Ahora vemos por bbdd
        $arrBbdd =

            $arrBbdd = DB::connection('segmentation')->table('bbdd_lists')->where('active', true)->select('id')->get();
        foreach ($arrBbdd as $elem) {
                # code...
            $id = $elem->id;
            Redis::sinterstore('temp:sinter:' . $id, ['temp:sinter', 'crm:db:' . $id]);
            $cont = Redis::scard('temp:sinter:' . $id);
            dump('Bbdd: ' . $id . ' cont: ' . $cont);
            $arrSinters[] = 'temp:sinter:' . $id;

        }
        //Y ahora borramos todo lo generado en temporal
        Redis::del($arrSinters);
        $time = microtime(true) - $start;

        dump('ALL: ' . ($time));



        dd('final ');
    }


    /**
     * Función de testeo de traer datos de redis y procesarlos. a ver cómo lo hacemos
     * Vamos a hacer un Union de valores por segmentación (or) y luego un sinter de todos los que haya
     * La diferencia es que los datos de los usuarios la tenemosen caché, por lo que no accederemos a bbdd sino que los traeremos y haremos un array_key_intersect...
     * Seguro que tarda más pero será más eficiente.
     * Tarda más además porque tendremos que traer los datos
     * 
     *
     * @return void
     */
    public function redisTestCacheVersion()
    {

        ini_set('memory_limit', '4G');
        set_time_limit(0);
        $s1 = microtime(true);
        $start = $s1;
        Redis::sunionstore('temp:pubs', [
            'crm:segmentation:publishers_821',
            'crm:segmentation:publishers_109',
            'crm:segmentation:publishers_15',
            'crm:segmentation:publishers_108',
            'crm:segmentation:publishers_52',
            'crm:segmentation:publishers_65',
            'crm:segmentation:publishers_33',
            'crm:segmentation:publishers_805',
            'crm:segmentation:publishers_161',
            'crm:segmentation:publishers_250',
            'crm:segmentation:publishers_200'
        ]);
        $s2 = microtime(true);
        dump('Cargado Pubs en ' . ($s2 - $s1));
        $s1 = microtime(true);
        Redis::sunionstore('temp:locations', [
            'crm:segmentation:locations_5',
            'crm:segmentation:locations_8',
            'crm:segmentation:locations_28',
            'crm:segmentation:locations_31',
            'crm:segmentation:locations_46',
            'crm:segmentation:locations_50'
        ]);
        $s2 = microtime(true);
        dump('Cargado Locagtions en ' . ($s2 - $s1));
        $s1 = microtime(true);

        Redis::sunionstore('temp:gtp', [
            'crm:gtp:surve_answers_111',
            'crm:gtp:surve_answers_110',
            'crm:gtp:surve_answers_4361',
            'crm:gtp:surve_answers_33097',
            'crm:gtp:surve_answers_4310',
            'crm:gtp:surve_answers_4315',
            'crm:gtp:surve_answers_49',
            'crm:gtp:surve_answers_4322',
            'crm:gtp:surve_answers_16663',
            'crm:gtp:surve_answers_16667',
            'crm:gtp:surve_answers_41227'
        ]);
        $s2 = microtime(true);
        dump('Cargado gtp en ' . ($s2 - $s1));
        $s1 = microtime(true);
        $resp = Redis::sinter(['temp:pubs', 'temp:locations', 'temp:gtp']);
        $s2 = microtime(true);
        dump('Cargado datos en ' . ($s2 - $s1));
        dump('Cont: ' . count($resp));
        Redis::del(['temp:pubs', 'temp:locations', 'temp:gtp']);

        $respData = array_flip($resp);

            //Cogemos listado bbdd de caché
        $bbddList = Cache::get('bbddList', []);
        foreach ($bbddList as $idBbdd => $garbage) {
    # code...
            $s1 = microtime(true);
            $arr = Cache::get('bbdd_' . $idBbdd,[]);
            $cont = count(array_intersect_key($respData, $arr));
            $s2 = microtime(true);
            dump('Bbdd ' . $idBbdd . ' Cont: ' . $cont . ' en ' . ($s2 - $s1));
        }
        dump('total: '.(microtime(true) - $start));

        die('fuera');
        
    }
}
