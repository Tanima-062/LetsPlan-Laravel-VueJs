<?php

namespace App\Exports;

use App\Models\ChildrenRegistration;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChildrenExport implements FromView
{
    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        // return view('exports.childrens', [
        //     'childrens' => ChildrenRegistration::where('company_id',auth()->user()->company_id)
        //                     ->with(['motherTongue','nationality','selectedSpielgruppen','closestSpielgruppen','slots','childrenRegistrationOpeningHours.spielgruppen'])->cursor()
        // ]);
        return view('exports.childrens', [
            'childrens' =>  $this->data
        ]);
    }
}
