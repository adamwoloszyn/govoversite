<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaItemType extends Model
{
    use HasFactory;

    protected $table = 'agenda_item_types';

    protected $primaryKey = 'id';


    public static function GetAgendaList()
    {
        return AgendaItemType::where( "is_enabled", "=", "1" )
            ->orderBy('order', 'asc')
            ->get();
    }   // end of GetAgendaList()
}   // end of AgendaItemType class
