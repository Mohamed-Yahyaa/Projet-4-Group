<?php

namespace App\Http\Controllers;

use App\Models\PreparationBrief;
use App\Models\PreparationTache;
use Illuminate\Http\Request;
use App\Exports\TaskExport;
use App\Imports\TaskImport;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class PreparationTacheController extends Controller
{

    function __construct()
    {
        // authorization formateur
        $this->middleware("can:isFormateur")->only(["destroy"]);
    }
    //index view
    public function index()
    {
        //afficher touts les briefs
        $brief=PreparationBrief::all();
        //pagination   
        $tasks =PreparationTache::paginate(3);
        // return view
        return view('tasks.index',['brief'=>$brief,'tasks'=>$tasks]);
    }
    // fitrage de brief
    public function filter_bief(Request $request){
        //filtre par preparation_brief_id
        $task=PreparationTache::where('Preparation_brief_id','Like','%'.$request->filter.'%')->get();
        // return view
        return response(['dataTask'=>$task]);
    }
    // searsh
    public function search_tache(Request $request){
        // searsh par nom de tache
        $searchtask=PreparationTache::where('Nom_tache','Like','%'.$request->searchtask.'%')->get();
        // return view
        return response(['search'=>$searchtask]);

    }
    // return page create tache
    public function create()
    {
        // afficher tous les briefs
        $brief=PreparationBrief::all();
        // return view
        return view('tasks.create',['brief'=>$brief]);
    }
    // stocker la tache dans BD
    public function store(Request $request)
    {
    // validation request
        $request->validate([
            'Nom_tache'=>'required|max:50',
            'Duree'=>'required'
        ]);
    //ajouter tache dans DB
        PreparationTache::create([
            'Nom_tache'=>$request->Nom_tache,
            'Description'=>$request->Description,
            'Duree'=>$request->Duree,
            'Preparation_brief_id'=>$request->Preparation_brief_id
        ]);

        return to_route('task.index');
    }
    // afficher tache pour editer
    public function edit($id)
    {
        // afficher la tache par id
        $edit=PreparationTache::findOrFail($id);
        // afficher tous les briefs
        $brief=PreparationBrief::all();
        // return view
        return view('tasks.edit',['edit'=>$edit,'brief'=>$brief]);
    }
    //modifer tache
    public function update(Request $request, $id)
    {
        // validation request
        $request->validate([
            'Nom_tache'=>'required|max:50',
            'Duree'=>'required'
        ]);
        // modifier tache par id
        $update=PreparationTache::findOrFail($id);
        $update->Nom_tache=$request->get('Nom_tache');
        $update->Description=$request->get('Description');
        $update->Duree=$request->get('Duree');
        $update->Preparation_brief_id=$request->get('Preparation_brief_id');
        $update->save();
        //return a page task
        return redirect('/task')->with('success');
    }
    //suprimmer tache
    public function destroy($id)
    {
        $delete = PreparationTache::findOrFail($id);
        $delete->delete();
        return redirect('/task');
    }

     // export data format excel

     public function exportexcel(){
        return Excel::download(new TaskExport,'datapage.xlsx');
    }

     // import data format excel
     public function importexcel(Request $request){

        Excel::import(new TaskImport, $request->file);
        return redirect()->back();

    }

    //  Export data form PDF

    public function generatepdf(){

        $tasks = PreparationTache::all();
        $pdf = Pdf::loadView('pdf.tasks', compact('tasks'));
    return $pdf->download('tasks.pdf');
    }

}
