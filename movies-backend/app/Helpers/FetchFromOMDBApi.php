<?php

namespace App\Helpers;

use App\Models\Movie;
use App\Models\Series;
use GuzzleHttp\Client;
use DB;
use Illuminate\Support\Collection;

class FetchFromOMDBApi {

    private $apikey;
    private $apiURL;
    private $mock;

    public function search($term, $page=0) {
        
        $this->apikey = env('OMDB_API_KEY', '');
        $this->apiURL = env('OMDB_API_URL', '');
        $this->mock = false;

        $query = "{$this->apiURL}?apikey={$this->apikey}&s={$term}" . ($page > 0 ? "&page={$page}":"");

        try {
            if($this->mock){
                echo "Test mode is on Fetched from file\r\n";
                $output = file_get_contents(storage_path("flat\\curlresults.json"));
            } else {
               
                $ch = curl_init($query);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);      
                curl_close($ch);
            }

            $response_data = json_decode($output);
            
            if($response_data->Response == "True"){
                $saved_collection = $this->SaveToLocal($response_data->Search);
                return $saved_collection;
            }
            else {
                return json_decode("{}");
            }
            
        }
        catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function SaveToLocal($data){

        $saved_items = new Collection();

        foreach($data as $datum){

            try{
                
                $exists = DB::select("SELECT imdbId
                                        FROM movies 
                                        WHERE imdbId = '{$datum->imdbID}'
                                        UNION SELECT imdbId 
                                               FROM series 
                                               WHERE imdbId = '{$datum->imdbID}'");
  
                if(count($exists) > 0){
                    echo "Skipped {$datum->Title} (already exists) \r\n";
                    continue;
                }

                DB::beginTransaction();
                if($datum->Type == 'movie'){
                    $saved = Movie::create([
                        'title' => $datum->Title,
                        'imdbId' => $datum->imdbID,
                        'year' => $datum->Year,
                        'poster' => $datum->Poster
                    ]);
                } else {
                    $saved = Series::create([
                        'title' => $datum->Title,
                        'imdbId' => $datum->imdbID,
                        'year' => $datum->Year,
                        'poster' => $datum->Poster
                    ]);
                }
                DB::commit();
                $saved_items->push($saved);
            }
            catch (\Exception $ex) {
                dd($ex);
                DB::rollback();
            }
        }
        return $saved_items;
    }

}