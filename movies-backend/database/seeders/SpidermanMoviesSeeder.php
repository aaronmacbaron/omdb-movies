<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Movie;
use App\Models\Series;

class SpidermanMoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $results = Movie::WHERE('title','like','%spider%man%')->count();
            if(empty($results)){
                $data = json_decode(file_get_contents(storage_path('flat\\spidermanMovies.json')));
                foreach($data->Search as $datum){
                    if($datum->Type == 'movie'){
                        Movie::create([
                            'title' => $datum->Title,
                            'imdbId' => $datum->imdbID,
                            'year' => $datum->Year,
                            'poster' => $datum->Poster
                        ]);
                    } else {
                        Series::create([
                            'title' => $datum->Title,
                            'imdbId' => $datum->imdbID,
                            'year' => $datum->Year,
                            'poster' => $datum->Poster
                        ]);
                    }
                }
            }
          
        } catch( \Exception $ex){
            dd($ex);
        }
    }
}
