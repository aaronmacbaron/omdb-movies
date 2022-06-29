<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MovieResource;
use App\Helpers\FetchFromOMDBApi;
use App\Helpers\FormattedResponse;
use Illuminate\Support\Collection;


class MovieController extends Controller
{
    /**
     * Display a listing of Movies.
     * usage: GET /api/movies
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Movie::latest()->get();
        $formatted_response = FormattedResponse::instance("True", MovieResource::collection($data));
        return response()->json($formatted_response);
    }


    /**
     * Store a newly created Movie in storage.
     * usage: POST /api/movies
     *        form-data :
     *          'title', 'year', 'poster', 'imdbId'
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'year' => 'required',
            'poster' => 'required',
            'imdbId' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $movie = Movie::create([
            'title' => $request->title,
            'year' => $request->year,
            'poster' => $request->poster,
            'imdbId' => $request->imdbId
         ]);

        $formatted_response = FormattedResponse::instance("True", new MovieResource($movie));

        return response()->json($formatted_response);
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
            $data = Movie::where('title','like',"%{$search_term}%")->get();
        }

        if(count($data) < 1){
            if($page > 0){
                $data = $externalAPI->search($term,$page);
            } else {
                $data = $externalAPI->search($term);
            } 
        }

        $formatted_response = FormattedResponse::instance("True", MovieResource::collection($data));

        return response()->json($formatted_response);
    }

    /**
     * Display the specified Movie.
     * usage: GET /api/movies/{id}
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::find($id);
        if (is_null($movie)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new MovieResource($movie)]);
    }

    /**
     * Update the specified Movie in storage.
     * usage: PATCH to /api/movies{id}?field_to_update=updated_data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
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
        $fields = ['title', 'year', 'poster', 'imdbId', 'liked'];

        foreach($fields as $field) {
            if(!empty($request->$field)){
                $movie->$field = $request->$field;
            }
        }

        $movie->save();
        
        return response()->json(['Movie updated successfully.', new MovieResource($movie)]);
    }

    /**
     * Remove the specified Movie from storage.
     * usage: DELETE /api/movies/{id}
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json('Movie deleted successfully');
    }
}
