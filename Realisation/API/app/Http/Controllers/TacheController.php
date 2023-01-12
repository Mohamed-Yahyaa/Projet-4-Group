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

        return view('tache.index',['tache'=>$tasks]);

    }
    // filtrage de brief
    public function filter_brief(Request $request){

        $tasks = Tache::where('preparation_brief_id', 'Like','%'.$request->filter.'%')->get();
        $tasks = Tache::where('Etat','Like','%' .$request->filterTache.'%')->get();

        return view(['dataTask'=>$tasks]);
    }

  public function Search_tache(Request $request){
    $searchTask = Tache::where('preparation_tache_id','Like','%'.$request->searchTask.'%')->get();

    return view((['search'=>$searchTask]));
  }


  public function edit($id){
    
   $edit = Tache::findOrfail($id);

   $tasks = Tache::all();

   return view('tache.edit',['edit'=>$edit,'tache'=>$tasks]);

  
  }

  public function update(Request $request , $id){
    $request->validate([
      'Etat'=>'required|max:50',
      'date_debut'=>'required',
      'date_fin'=>'required'
  ]);

  $update=Tache::findOrFail($id);
  $update->Etat=$request->get('Etat');
  $update->date_debut=$request->get('date_debut');
  $update->date_fin=$request->get('date_fin');
  $update->preparation_tache_id=$request->get('preparation_tache_id');
  $update->save();

  return redirect('/tache')->with('success');

  }

  public function destroy($id)
  {
      $delete = Tache::findOrFail($id);
      $delete->delete();
      return redirect('/tache');
  }
}
