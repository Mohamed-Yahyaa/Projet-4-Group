<?php

namespace App\Http\Controllers;


use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    //
    function __construct()
    {
      $this->middleware("")  ;
    }

    //view index
    public function index(){
        
      // afficher touts les taches
        $tasks = Tache::all();
        // Pagiantion
        $tasks = Tache::paginate(3);

        return view('tasks.index',['tasks'=>$tasks]);

    }
    // filtrage de brief
    public function filter_brief(Request $request){

        $task = Tache::where('preparation_brief_id', 'Like','%'.$request->filter.'%')->get();
        $task = Tache::where('Etat','Like','%' .$request->filter.'%')->get();

        return view(['dataTask'=>$task]);


    }
}
