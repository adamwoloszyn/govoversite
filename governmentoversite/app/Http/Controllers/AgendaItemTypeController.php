<?php

namespace App\Http\Controllers;

use App\Models\AgendaItemType;
use Illuminate\Http\Request;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class AgendaItemTypeController extends Controller
{
    public $entriesPerPage = 10;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = AgendaItemType::paginate($this->entriesPerPage);

        //dd($items->first()->VideoCategory);

        return view( "agendaitemtypes.index" )->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agendaitemtypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newAgendaItemType = new AgendaItemType;
        $newAgendaItemType->short_description = $request->short_description;
        $newAgendaItemType->long_description = $request->long_description;
        $newAgendaItemType->template = $request->template;
        $newAgendaItemType->order = $request->order;
        $newAgendaItemType->save();

        return redirect()->route('agendaitemtypes.index')->with('success', 'Agenda Item Type created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $agendaItemType = AgendaItemType::find($id);
        return view('agendaitemtypes.edit')->with('agendaItemType', $agendaItemType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $agendaItemType = AgendaItemType::findOrFail($id);
        $agendaItemType->short_description = $request->short_description;
        $agendaItemType->long_description = $request->long_description;
        $agendaItemType->template = $request->template;
        $agendaItemType->order = $request->order;
        $agendaItemType->save();

        return redirect()->route('agendaitemtypes.index')->with('success', 'Agenda Item Type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        $agendaItemType = AgendaItemType::find($id);
        $agendaItemType->delete();

        return redirect()->route('agendaitemtypes.index')->with('success', 'Agenda Item Type deleted successfully!');
    }
}
