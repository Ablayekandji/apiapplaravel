<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cars = Cars::all();
        if (count($cars) <= 0) {
            return response(["message" => "pas de voitures"], 200);
        } else {
            return response($cars, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //niania
        $this->validate($request, [
            "model" => "required|string",
            "price" => "required|numeric",
            "description" => "required|string|min:5",
            "user_id" => "required|numeric"
        ]);
        $auto = new Cars();
        $auto->model = $request->model;
        $auto->price = $request->price;
        $auto->description = $request->description;
        $auto->user_id = $request->user_id;
        $auto->save();

        return response()->json(['message' => "auto ajouté avec succès."], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Cars::findOrFail($id);
        // $auto = DB::table("cars")
        //     ->join("users", "cars.user_id", "=", "users.id")
        //     ->select("cars.*", "users.name", "users.email")
        //     ->where("cars.id", "=", $id)
        //     ->get()
        //     ->first();
        // return $auto;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $auto = $this->validate($request, [
            "model" => "required|string",
            "price" => "required|numeric",
            "description" => "required|string|min:5",
            "user_id" => "required|numeric"
        ]);
        // $auto = new Cars;
        // $auto->model = $request->model;
        // $auto->price = $request->price;
        // $auto->description = $request->description;
        // $auto->user_id = $request->user_id;
        $autotrouve = Cars::find($id);
        if (!$autotrouve) {
            return response()->json(['message' => "pas de voiture avec cet id=> $id"], 404);
        } else if ($autotrouve->user_id != $auto['user_id']) {
            return response()->json(['message' => "pas d'autorisation pour effectuer ça!!!"], 403);
        } else {
            $autotrouve->update($auto);
            return response()->json(['message' => "MAJ avec succes!!!"], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //

        $auto = $this->validate($request, [

            "user_id" => "required|numeric"
        ]);
        $autotrouve = Cars::find($id);
        if (!$autotrouve) {
            return response()->json(['message' => "pas de voiture avec cet id=> $id"], 404);
        } else if ($autotrouve->user_id != $auto['user_id']) {
            return response()->json(['message' => "pas d'autorisation pour effectuer ça!!!"], 403);
        }
        Cars::destroy($id);
        return response()->json(['message' => "Supprimer avec succes!!!"], 200);
    }
}
