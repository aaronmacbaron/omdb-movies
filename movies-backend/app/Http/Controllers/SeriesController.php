<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SeriesResource;
use App\Helpers\FetchFromOMDBApi;
use Illuminate\Support\Collection;
class SeriesController extends Controller
{
     /**
     * Display a listing of Series.
     * usage: GET /api/Series
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Series::latest()->get();
        return response()->json([SeriesResource::collection($data), 'Series fetched.']);
    }


    /**
     * Store a newly created Series in storage.
     * usage: POST /api/Series
     *        form-data :
     *          'title', 'year', 'poster', 'imdbId'
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'year' => 'required',
            'poster' => 'required',
            'imdbId' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $series = Series::create([
            'title' => $request->title,
            'year' => $request->year,
            'poster' => $request->poster,
            'imdbId' => $request->imdbId
         ]);
        
        return response()->json(['Series created successfully.', new SeriesResource($series)]);
    }

    /**
     * Search for specific term in the database first, if not found query api and retrieve data.
     * If a page parameter is specified it skips the database and goes right to the api
     */
    public function search($term, $page = 0)
    {

        $externalAPI = new FetchFromOMDBApi();
        $search_term = preg_replace('/[^A-Za-z0-9\-]/', '%', $term);
        $data = new Collection();
        if($page == 0) {
            $data = Series::where('title','like',"%{$search_term}%")->get();
        }

        if(count($data) < 1){
            if($page > 0){
                $data = $externalAPI->search($term,$page);
            } else {
                $data = $externalAPI->search($term);
            } 
        }

        return response()->json([SeriesResource::collection($data), 'Series fetched.']);
    }

    /**
     * Display the specified Series.
     * usage: GET /api/Series/{id}
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $series = Series::find($id);
        if (is_null($series)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new SeriesResource($series)]);
    }

    /**
     * Update the specified Series in storage.
     * usage: PATCH to /api/Series{id}?field_to_update=updated_data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Series $series)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'string|string|max:255',
            'year' => 'string',
            'poster' => 'string',
            'imdbId' => 'string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
        $fields = ['title', 'year', 'poster', 'imdbId'];

        foreach($fields as $field) {
            if(!empty($request->$field)){
                $seriese->$field = $request->$field;
            }
        }

        $series->save();
        
        return response()->json(['Series updated successfully.', new SeriesResource($series)]);
    }

    /**
     * Remove the specified Movie from storage.
     * usage: DELETE /api/Series/{id}
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series)
    {
        $series->delete();

        return response()->json('Series deleted successfully');
    }
}
