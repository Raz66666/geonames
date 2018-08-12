<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
//use MichaelDrennen\Geonames\Controllers\GeonamesController;
//use MichaelDrennen\Geonames\Models\AlternateName;
//use MichaelDrennen\Geonames\Models\Geoname;

class MapController extends Controller
{

    protected $gmap;

    public function __construct(){

    }

    public function index(){
        return view('map');
    }

    public function getcities(Request $request){
        $name = $request->get('name');
        $lang = $request->get('lang');
        $limit = $request->get('count');

        if($limit > 51){
            $limit = 51;
        }

        $city = DB::select('SELECT geonames_alternate_names.geonameid,geonames_alternate_names.alternate_name,geonames.latitude,geonames.longitude FROM geonames_alternate_names LEFT JOIN geonames ON geonames_alternate_names.geonameid=geonames.geonameid  WHERE isolanguage = :lang AND alternate_name = :name LIMIT 1', ['lang'=>$lang, 'name'=>$name]);
        if(count($city) == 0){
            return json_encode(array(false));
        }
        $lat = $city[0]->latitude;
        $lng = $city[0]->longitude;

        $radius = 50;

        $count = 0;


        $query = "SELECT geonames.geonameid,
                        geonames.latitude,
                        geonames.longitude,
                        min(geonames_alternate_names.alternate_name) AS alternate_name,
                        ( 6371 * acos( cos( radians( :lat ) ) * cos( radians( geonames.latitude ) ) * cos( radians( geonames.longitude ) - radians( :lng ) ) + sin( radians( :lat2 ) ) * sin( radians( geonames.latitude ) ) ) ) AS distance
                  FROM geonames_alternate_names
                  LEFT JOIN geonames ON geonames.geonameid=geonames_alternate_names.geonameid
                  WHERE geonames_alternate_names.isolanguage = :lang AND geonames.feature_code = 'PPL'
                  GROUP BY geonames.geonameid,geonames.latitude,geonames.longitude
                  HAVING distance < :radius ORDER BY distance LIMIT 0 , :limit";

        $result = DB::select($query, ['lat'=>$lat, 'lng'=>$lng, 'lat2'=>$lat, 'radius'=>$radius, 'lang' => $lang, 'limit' => $limit]);


        $count = count($result);
        while ($count < $limit) {
            $radius = $radius + $radius * 0.5;

            $result = DB::select($query, ['lat'=>$lat, 'lng'=>$lng, 'lat2'=>$lat, 'radius'=>$radius, 'lang' => $lang, 'limit' => $limit]);

            $count = count($result);
        }

        return json_encode($result);

    }

    public function getcitiesNameOption(Request $request){

        $name = $request->get('name');

        $result = DB::table('geonames_alternate_names')->select(DB::raw('max(alternate_name) as alternate_name'))
                        ->where('alternate_name', 'like', '%'.$name.'%')
                        ->where('isolanguage', '<>', 'link')
                        ->groupBy('alternate_name')
                        ->limit('20')
                        ->get();


        return response()->json($result);

    }

}
