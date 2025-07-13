<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\AgendaItemType;

class AgendaItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agendaItemTypes = [
            [
                'short_description'=>'Main Template',
                'long_description'=> 'Main Template',
                'order'=>1,
                'template'=>"<div>" . 
                    "<br>" . 
                    "AGENDA ITEM: xx   <br>" . 
                    "AGENDA DETAILS: xx<br>" . 
                    "SYNTAX: xx<br>" . 
                    "SYNTAX: xx<br>" . 
                    "SYNTAX: xx<br>" . 
                    "SYNTAX: xx<br>" . 
                    "SYNTAX: xx<br>" . 
                    "TIME OUT: xx:xx<br>" . 
                    "TIME IN: xx::xx<br>" . 
                    "DATE: xx<br>" . 
                    "ACCOUNTS PAYABLE: xx<br>" . 
                    "DATE: xx<br>" . 
                    "PAYROLL: xx<br>" . 
                    "DATE: xx<br>" . 
                    "ACCOUNTS PAYABLE: xx<br>" . 
                    "DATE: xx<br>" . 
                    "PAYROLL: xx<br>" . 
                    "DATA LINK: xx <br>" . 
                    "SYNOPSIS: xx <br>" . 
                    "________________________________________________________<br>" . 
                    "MOTION:<br>" . 
                    "MOTIONER: xx <br>" .            
                    "MOTION DETAILS: xx<br>" . 
                    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx<br>" . 
                    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx<br>" . 
                    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx<br>" . 
                    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx<br>" . 
                    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx<br>" . 
                    "DATE: xx<br>" . 
                    "MAP: xx <br>" . 
                    "LOT: xx <br>" . 
                    "ACREAGE: xx <br>" . 
                    "TAX STATUS: xx <br>" . 
                    "CEMETARY: xx			<br>" . 								
                    "AMOUNT: xx<br>" . 
                    "SECOND: xx<br>" . 
                    "AMENDED: xx<br>" . 
                    "SECOND: xx       <br>" .  
                    "DISCUSSION: xx<br>" . 
                    "SELECTMEN: xx   VOTE: xx  <br>" . 
                    "SELECTMEN: xx   VOTE: xx  <br>" . 
                    "SELECTMEN: xx   VOTE: xx  <br>" . 
                    "SELECTMEN: xx   VOTE: xx<br>" . 
                    "SELECTMEN: xx   VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "YES: xx NO: xx<br>" . 
                    "___________________________________________________________<br>" . 
                    "MOTION DETAILS: (ENTER INTO) Non public session:<br>" . 
                    "SESSION #1: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #2: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #3: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #4: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #5: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #6: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SECOND: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "YES: xx NO: xx<br>" . 
                    "TIME IN: xx:xx<br>" . 
                    "_____________________________________________________________<br>" . 
                    "MOTION DETAILS: (EXIT) Non public session:<br>" . 
                    "TIME OUT: xx:xx<br>" . 
                    "SECOND: xx  <br>" . 
                    "DISCUSSION: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "_______________________________________________________________<br>" . 
                    " MOTION DETAILS: Seal  <br>" . 
                    "SESSION #1: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #2: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #3: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #4: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #5: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #6: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SECOND: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "SELECTMEN: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "COMMISSIONER: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 	
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "REPRESENTATIVE: xx VOTE: xx<br>" . 
                    "YES: xx NO: xx<br>" . 
                    "_________________________________________________________________<br>" . 
                    "REPORT: <br>" . 
                    "REPORTER: xx <br>" . 
                    "REPORT DETAILS: No vote to seal <br>" . 
                    "REPORT:<br>" . 
                    "REPORTER: xx<br>" . 
                    "REPORT DETAILS: No vote to seal<br>" . 
                    "SESSION #1: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #2: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #3: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #4: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #5: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "SESSION #6: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx<br>" . 
                    "NOTE: xx<br></div>",
            ],
        ];

        foreach($agendaItemTypes as $agendaItemType)
        {
            AgendaItemType::create($agendaItemType);
        }
    }
}
