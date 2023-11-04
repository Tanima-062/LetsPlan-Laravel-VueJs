<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Planung;
use App\Models\Division;
use App\Models\OpeningHour;
use App\Models\Spielgruppen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ChildrenRegistration;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PlannungRequest;
use App\Http\Resources\ChildrenResource;
use App\Http\Resources\SpielgruppenResource;
use App\Models\ChildrenRegistrationOpeningHour;

class PlannungController extends Controller
{

    // Old Code
    // public function index(Request $request)
    // {
    //     try {
    //         if ($request->filled('spielgruppen_id')) {
    //             $spielgruppen = Spielgruppen::with('openingHours', 'openingHours.lead', 'openingHours.assistant')->findOrFail($request->spielgruppen_id);
    //             $this->extracted($spielgruppen);
    //             return response()->json(SpielgruppenResource::collection($spielgruppen));
    //         } else {
    //             $spielgruppens = Spielgruppen::with('openingHours', 'openingHours.lead', 'openingHours.assistant','childrenRegistrations')
    //                 ->where('company_id', Auth::user()->company_id)
    //                 ->when(Auth::user()->role_id == User::PLAYGROUP_LEADER,function ($query){
    //                     $query->active()->whereIn('id',  OpeningHour::where('lead_id', Auth::id())->pluck('spielgruppen_id'));
    //                 })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT,function ($query){
    //                     $query->active()->whereIn('id', OpeningHour::where('assistant_id', Auth::id())->pluck('spielgruppen_id'));
    //                 })
    //                 ->orderBy('order')->get();

    //             foreach ($spielgruppens as $spielgruppen) {
    //                 $this->extracted($spielgruppen);
    //             }
    //             return response()->json($spielgruppens);
    //         }
    //     } catch (\Exception $exception) {
    //         Log::info($exception->getMessage());
    //         return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
    //     }
    // }
    public function index(Request $request)
    {
        try {
            $activeDivision = Division::query()
                    ->where('company_id', \auth()->user()->company_id)
                    ->when(request('classification'), function($q){
                        $q->where('id', request('classification'));
                    },function($q){
                        $q
                        ->where('status_id', 2)
                        ;
                    })
                    ->first()
                    ;

            if ($request->filled('spielgruppen_id')) {
                $spielgruppen = Spielgruppen::with('openingHours', 'openingHours.lead', 'openingHours.assistant')->findOrFail($request->spielgruppen_id);
                $this->extracted($spielgruppen, $activeDivision);
                return response()->json(SpielgruppenResource::collection($spielgruppen));
            } else {
                $spielgruppens = Spielgruppen::with(['openingHours', 'openingHours.lead', 'openingHours.assistant','childrenRegistrations'=>function($q){
                    $q->ActiveAndPending();
                }])
                    ->where('company_id', Auth::user()->company_id)
                    ->where('status_id', 1)
                    // ->when(Auth::user()->role_id == User::PLAYGROUP_LEADER,function ($query){
                    //     $query->active()->whereIn('id',  OpeningHour::where('lead_id', Auth::id())->pluck('spielgruppen_id'));
                    // })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT,function ($query){
                    //     $query->active()->whereIn('id', OpeningHour::where('assistant_id', Auth::id())->pluck('spielgruppen_id'));
                    // })
                    ->when(request('playgroup') && !is_null(request('playgroup')), function($q){
                        $playgroups = explode(',', request('playgroup'));
                        $q->whereIn('id', $playgroups);
                    })
                    // ->when(request('classification') && !is_null(request('classification')), function($q){
                    //     $q->whereHas('childrenRegistrations', function($q){
                    //         $classifications = explode(',', request('classification'));
                    //         $q->whereIn('division_id', $classifications);
                    //     });
                    // }, function($q){
                    //     $q->whereHas('childrenRegistrations', function($q){
                    //         $q->whereHas('division', function($q){
                    //             $q->where('status_id', 2);
                    //         });
                    //     });
                    // })
                    ->when(request('playgroup_leader') && !is_null(request('playgroup_leader')), function($q){
                        $q->whereHas('openingHours', function($q){
                            $playgroupLeaders = explode(',', request('playgroup_leader'));
                            $q->whereIn('lead_id', $playgroupLeaders);
                        });
                    })
                    ->when(request('assistant') && !is_null(request('assistant')), function($q){
                        $q->whereHas('openingHours', function($q){
                            $playgroupAssistants = explode(',', request('assistant'));
                            $q->whereIn('assistant_id', $playgroupAssistants);
                        });
                    })
                    ->when(request('child') && !is_null(request('child')), function($q){
                        $q->whereHas('childrenRegistrations', function($q){
                            $childrens = explode(',', request('child'));
                            $q->whereIn('children_registration_id', $childrens);
                        });
                    })
                    ->when(request('marking') && !is_null(request('marking')), function($q){
                        $q->whereHas('childrenRegistrations', function($q){
                            $markings = explode(',', request('marking'));
                            $q->whereIn('children_registration_id', $markings);
                        });
                    })
                    ->when(request('query'), function($q){
                        $q->where(function($q){
                            $q->whereHas('childrenRegistrations', function($q){
                                $query = request('query');
                                $q
                                    ->where('child_first_name', 'LIKE', "$query%")
                                    ->orWhere('child_last_name', 'LIKE', "$query%")
                                    ->orWhereRaw("CONCAT(`child_first_name`, ' ', `child_last_name`) LIKE ?", [$query . '%'])
                                    // ->orWhere('child_birthday', 'LIKE', "$query%")
                                ;

                                //Search by birth day
                                // ([0-9]{2}).([0-9]{2}).([0-9]{4})
                                if(preg_match("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $query, $matches)){
                                    $date = sprintf("%s-%s-%s", $matches[3], $matches[2], $matches[1]);
                                    $q->orWhere('child_birthday', 'LIKE', "$date%");
                                }

                            });
                        });
                    })
                    ->orderBy('order')
                    ->get()
                    // ->toSql()
                    ;
                // return $spielgruppens;



                foreach ($spielgruppens as $spielgruppen) {
                    // $this->extracted($spielgruppen, $activeDivision);
                    $this->extractedNew($spielgruppen, $activeDivision);
                }

                $loadDivision =  null;
                if(!$activeDivision){
                   $loadDivision =  Division::query()
                    ->where('company_id', \auth()->user()->company_id)
                    ->where('status_id', 1)
                    ->first()
                    ;
                }

                // file_put_contents('planning.json', $spielgruppens);

                return response()->json(['playgroups'=>$spielgruppens, 'active_division'=>$activeDivision ?? $loadDivision]);
            }
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }


    private function extractedNew($spielgruppen, $activeDivision): void
    {
        $spielgruppenOpeningHours = $spielgruppen->openingHours;


        if ($spielgruppen->childrenRegistrations->count()) {
            foreach ($spielgruppenOpeningHours as $openingHour) {
                $query = $spielgruppen->childrenRegistrations()
                    ->ActiveAndPending()
                    ->where('day_id',$openingHour->day_id)
                    ->where('hour_id',$openingHour->hour_id)
                    ->when(request('classification') && !is_null(request('classification')), function($q){
                        $classifications = explode(',', request('classification'));
                        $q->whereIn('division_id', $classifications);
                    }, function($q) use($activeDivision){
                        $q->whereHas('division', function($q) use ($activeDivision) {
                            if($activeDivision) {
                                $q->where('id', $activeDivision->id);
                            }else {
                                $q->where('status_id', 1);
                            }
                        });
                    })
                    ->orderBy('order');
                $openingHour->countPerDay = $query->count();

                $eachChildOfSpecificDayHour = $query->get();
                $arr = [];
                foreach ($eachChildOfSpecificDayHour as $item) {
                    $arr[] = [
                        'id'=>$item->pivot->id,
                        'color' => $item->pivot->color,
                        'full_name' => $item->fullName,
                        'child_reg_id' => $item->id,
                        'spielgruppen_id' => $spielgruppen->id
                    ];
                }
                $openingHour->children = $arr;
            }

        }

        $childrenOpeningHours = ChildrenRegistrationOpeningHour::query()
            ->where('spielgruppen_id', $spielgruppen->id)
            ->with(['childrenRegistration'])
            ->get()
        ;
        // info($childrenOpeningHours);

        $collection = collect($spielgruppenOpeningHours);
        foreach ($childrenOpeningHours as $openingHour) {
            if(!$collection->contains(function ($val, $key) use ($openingHour) {
                return $val->day_id == $openingHour->day_id && $val->hour_id == $openingHour->hour_id;
            })){
                $collection->push($openingHour);


                $findItem = false;
                $currentCollection = $collection->where('day_id', $openingHour->day_id)->where('hour_id', $openingHour->hour_id)->first();
                if($currentCollection) {
                    $findItem = collect($currentCollection->children)->where('child_reg_id', $openingHour->childrenRegistration->id)->first();
                }


                $arr = [];
                if(!$findItem){
                    $arr[] = [
                        'id'=> $openingHour->id,
                        // 'color' => $openingHour->color,
                        'color' => '#e62d4fb3',
                        'full_name' => $openingHour->childrenRegistration->fullName,
                        'child_reg_id' => $openingHour->childrenRegistration->id,
                        'spielgruppen_id' => $spielgruppen->id
                    ];
                }

                $openingHour->children =collect($arr)->unique('child_reg_id');
                //
                $openingHour->lead_id = null;
                $openingHour->assistant_id = null;
                $openingHour->lead = null;
                $openingHour->assistant = null;
                $openingHour->countPerDay = $openingHour->children->count();
                unset($openingHour['childrenRegistration']);
            }
            ;
        }

        unset($spielgruppen['openingHours']);
        $spielgruppen->opening_hours = $collection;
        // info($collection);
    }

    /**
     * @param $spielgruppen
     * @return void
     */
    private function extracted($spielgruppen, $activeDivision): void
    {
        $spielgruppenOpeningHours = $spielgruppen->openingHours;

        if ($spielgruppen->childrenRegistrations->count()) {
            foreach ($spielgruppenOpeningHours as $openingHour) {
                $query = $spielgruppen->childrenRegistrations()
                    ->ActiveAndPending()
                    ->where('day_id',$openingHour->day_id)
                    ->where('hour_id',$openingHour->hour_id)
                    ->when(request('classification') && !is_null(request('classification')), function($q){
                        $classifications = explode(',', request('classification'));
                        $q->whereIn('division_id', $classifications);
                    }, function($q) use($activeDivision){
                        $q->whereHas('division', function($q) use ($activeDivision) {
                            if($activeDivision) {
                                $q->where('id', $activeDivision->id);
                            }else {
                                $q->where('status_id', 1);
                            }
                        });
                    })
                    ->orderBy('order');
                $openingHour->countPerDay = $query->count();

                $eachChildOfSpecificDayHour = $query->get();
                $arr = [];
                foreach ($eachChildOfSpecificDayHour as $item) {
                    $arr[] = [
                        'id'=>$item->pivot->id,
                        'color' => $item->pivot->color,
                        'full_name' => $item->fullName,
                        'child_reg_id' => $item->id,
                        'spielgruppen_id' => $spielgruppen->id
                    ];
                }
                $openingHour->children = $arr;
            }
        }

        // $spielgruppenOpeningHours = $spielgruppen->openingHours;

        // if ($spielgruppen->childrenRegistrations->count()) {
        //     foreach ($spielgruppenOpeningHours as $openingHour) {
        //         $query = $spielgruppen->childrenRegistrations()
        //             ->where('day_id',$openingHour->day_id)
        //             ->where('hour_id',$openingHour->hour_id)
        //             ->when(request('classification') && !is_null(request('classification')), function($q){
        //                 $classifications = explode(',', request('classification'));
        //                 $q->whereIn('division_id', $classifications);
        //             }, function($q){
        //                 $q->whereHas('division', function($q){
        //                     $q->where('status_id', 2);
        //                 });
        //             })
        //         ;

        //         $query = $this->playgroupFilterQuery($query);
        //         $query->orderBy('order');
        //         $openingHour->countPerDay = $query->count();

        //         $eachChildOfSpecificDayHour = $query->get();
        //         $arr = [];
        //         foreach ($eachChildOfSpecificDayHour as $item) {

        //             $arr[] = [
        //                 'id'=>$item->pivot->id,
        //                 'color' => $item->pivot->color,
        //                 'full_name' => $item->fullName,
        //                 'child_reg_id' => $item->id,
        //                 'spielgruppen_id' => $spielgruppen->id
        //             ];
        //         }
        //         $openingHour->children = $arr;
        //     }
        // }
    }

    public function playgroupFilterQuery($query)
    {
        $query->when(request('playgroup_leader') && !is_null(request('playgroup_leader')), function($q){
            $q->whereHas('selectedSpielgruppen', function($q){
                $q->FilterByPlaygroupLeader(request('playgroup_leader') );
            });
        })
        ->when(request('assistant') && !is_null(request('assistant')), function($q){
            $q->whereHas('selectedSpielgruppen', function($q){
                $q->FilterByPlaygroupLeader(request('playgroup_leader') );
            });
        })
        ->when(request('child') && !is_null(request('child')), function($q){
            $childrens = explode(',', request('child'));
            $q->whereIn('children_registration_id', $childrens);
        })
        ->when(request('marking') && !is_null(request('marking')), function($q){
            $markings = explode(',', request('marking'));
            $q->whereIn('children_registration_id', $markings);
        })
        ->when(request('query'), function($q){
            $q->where(function($q){
                $query = request('query');
                $q
                    ->where('child_first_name', 'LIKE', "$query%")
                    ->orWhere('child_last_name', 'LIKE', "$query%")
                    ->orWhereRaw("CONCAT(`child_first_name`, ' ', `child_last_name`) LIKE ?", [$query . '%'])

                ;
                if(preg_match("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $query, $matches)){
                    $date = sprintf("%s-%s-%s", $matches[3], $matches[2], $matches[1]);
                    $q->orWhere('child_birthday', 'LIKE', "$date%");
                }
            });
        });

        return $query;
    }

    public function update(PlannungRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $childRegistration = ChildrenRegistration::findOrFail($request->children_registration_id);
                $childRegOpeningHour = ChildrenRegistrationOpeningHour::where('children_registration_id', $childRegistration->id)->where('day_id', $request->day_id_from)->where('hour_id', $request->hour_id_from)->first();

                $childRegOpeningHour->delete();

                ChildrenRegistrationOpeningHour::create([
                    'children_registration_id' => $childRegistration->id,
                    'day_id' => 'day_id_to',
                    'hour_id' => 'hour_id_to',
                    'color' => 'green',
                    'created_from' => 'plannung'
                ]);
            });
            return response()->json('Updated');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }

    public function childInformation(ChildrenRegistration $childrenRegistration)
    {
        return (new ChildrenResource($childrenRegistration))->additional([
            'data'  =>  [
                // 'children_registration_count'   =>  $childrenRegistration->slots()->count(),
                'division'  =>  $childrenRegistration->division
            ]
        ]);
    }
    public function order(Request $request){
        $spielgruppens=$request->all();
        if (empty($spielgruppens) || !is_array($spielgruppens)){
            return;
        }
        foreach ($spielgruppens as $spielgruppen){
            Spielgruppen::find($spielgruppen['id'])->update([
                'order'=>intval($spielgruppen['order'])
            ]);
        }
    }
    public function orderChildrenInsideSameSlot(Request $request){
        $childrens = $request->all();
        if (empty($childrens) || !is_array($childrens)){
            return;
        }
        foreach ($childrens as $children){
            ChildrenRegistrationOpeningHour::find($children['id'])->update([
                'order'=>intval($children['order'])
            ]);
        }
    }
    public function changeChildrenPlaygroup(Request $request){
        $data = $request->all();
        $childrenRegistrationOpeningHour = ChildrenRegistrationOpeningHour::find($data['id']);
        if ($childrenRegistrationOpeningHour){

            $childrenRegistrationOpeningHour->update(
                [
                    'color'=>'white',
                    'day_id' => $data['day'],
                    'hour_id' => $data['period'],
                    'spielgruppen_id' => $data['playgroup'],
                    'order' => $data['order'] ?? 0,
                ]
            );
        }

    }
    public function childrenStatistics(){

        return response()->json([
            'green'=>$this->countChildrenByColor(ChildrenRegistrationOpeningHour::GREEN),
            'orange'=>$this->countChildrenByColor(ChildrenRegistrationOpeningHour::ORANGE),
            'yellow'=>$this->countChildrenByColor(ChildrenRegistrationOpeningHour::YELLOW),
            'red'=>$this->countChildrenByColor(ChildrenRegistrationOpeningHour::RED),
        ]);
    }
    public function countChildrenByColor($color){
       return ChildrenRegistrationOpeningHour::where('color',$color)->whereHas('childrenRegistration',function ($query){
            return $query->where('company_id',\auth()->user()->company_id);
        })->distinct('children_registration_id')->count();
    }

    public function status($status){

        $planung = Planung::where('company_id',\auth()->user()->company_id)->first();
        if ($planung){
            $planung->update([
               'status'=>$status
            ]);
            return response()->json('Saved');
        }
       return Planung::create([
               'company_id'=>\auth()->user()->company_id,
               'status'=>$status
        ]);

    }
    public function getActiveStatus(){
        return response()->json( Planung::where('company_id',\auth()->user()->company_id)->first());
    }


    public function deleteChild(Request $request, ChildrenRegistrationOpeningHour $childrenRegistrationOpeningHour)
    {
        // return $childrenRegistrationOpeningHour;

        // info($childrenRegistrationOpeningHour);

        $childrenRegistrationOpeningHour->forceDelete();

        return response('Child remove from playgroup successfully!');
    }


    /**
     * Get Planning filter data by column
     *
     * @param Request $request
     * @return void
     */
    public function getFilterableData(Request $request)
    {
        $request->validate([
            'column'        =>  ['required'],
        ]);

        $playgroupQuery =  Spielgruppen::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('status_id', 1)
            ->when(request('playgroup') && !is_null(request('playgroup')), function($q){
                $playgroups = explode(',', request('playgroup'));
                $q->whereIn('id', $playgroups);
            })
            // ->when(request('classification') && !is_null(request('classification')), function($q){
            //     $q->whereHas('childrenRegistrations', function($q){
            //         $classifications = explode(',', request('classification'));
            //         $q->whereIn('division_id', $classifications);
            //     });
            // }, function($q){
            //     $q->whereHas('childrenRegistrations', function($q){
            //         $q->whereHas('division', function($q){
            //             $q->where('status_id', 1);
            //         });
            //     });
            // })
            ->when(request('playgroup_leader') && !is_null(request('playgroup_leader')), function($q){
                $q->whereHas('openingHours', function($q){
                    $playgroupLeaders = explode(',', request('playgroup_leader'));
                    $q->whereIn('lead_id', $playgroupLeaders);
                });
            })
            ->when(request('assistant') && !is_null(request('assistant')), function($q){
                $q->whereHas('openingHours', function($q){
                    $playgroupAssistants = explode(',', request('assistant'));
                    $q->whereIn('assistant_id', $playgroupAssistants);
                });
            })
            ->when(request('child') && !is_null(request('child')), function($q){
                $q->whereHas('childrenRegistrations', function($q){
                    $childrens = explode(',', request('child'));
                    $q->whereIn('children_registration_id', $childrens);
                });
            })
            ->when(request('marking') && !is_null(request('marking')), function($q){
                $q->whereHas('childrenRegistrations', function($q){
                    $markings = explode(',', request('marking'));
                    $q->whereIn('children_registration_id', $markings);
                });
            })
            ->when(request('query'), function($q){
                $q->where(function($q){
                    $q->whereHas('childrenRegistrations', function($q){
                        $query = request('query');
                        $q
                            ->where('child_first_name', 'LIKE', "$query%")
                            ->orWhere('child_last_name', 'LIKE', "$query%")
                            ->orWhereRaw("CONCAT(`child_first_name`, ' ', `child_last_name`) LIKE ?", [$query . '%'])
                        ;
                        if(preg_match("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $query, $matches)){
                            $date = sprintf("%s-%s-%s", $matches[3], $matches[2], $matches[1]);
                            $q->orWhere('child_birthday', 'LIKE', "$date%");
                        }
                    });
                });
            })
            ->when($request->column == 'playgroup', function($q){
                $q->select('id', 'name');
            })
            // ->when($request->column == 'classification', function($q){
            //     $q->select('id');
            // })
            ->when($request->column == 'playgroup_leader', function($q){
                $q->select('id');
            })
            ->when($request->column == 'assistant', function($q){
                $q->select('id');
            })
            ->when($request->column == 'child', function($q){
                $q->select('id');
            })
            ->when($request->column == 'marking', function($q){
                $q->select('id');
            })
        ;

        if($request->column == 'playgroup'){
            $result = $playgroupQuery->get();

            return response($result);
        }
        // else if($request->column == 'classification'){
        //     $spielgruppens = $playgroupQuery
        //     ->pluck('id')
        //     ->toArray()
        //     ;

        //     $divisions_id = ChildrenRegistration::query()
        //         ->select('division_id')
        //         ->whereIn('spielgruppen_id', $spielgruppens)
        //         ->distinct()
        //         ->pluck('division_id')
        //         ->toArray()
        //         ;

        //     $divisions = Division::query()
        //             ->select(['id', 'name', 'status_id'])
        //             ->whereIn('id', $divisions_id)
        //         ->get()
        //     ;

        //     return response($divisions);
        // }
        else if($request->column == 'playgroup_leader'){
            $spielgruppens = $playgroupQuery
                ->pluck('id')
                ->toArray()
                ;

            $leadId = OpeningHour::query()
                    ->select('lead_id')
                    ->whereIn('spielgruppen_id', $spielgruppens)
                    ->distinct()
                    ->pluck('lead_id')
                    ->toArray()
                    ;

            $leads = User::query()
                        ->select(['id', 'first_name', 'last_name'])
                        ->whereIn('id', $leadId)
                    ->get()
                    ->map(fn($item)=> ['id'=>$item->id, 'name'=> $item->first_name.' '.$item->last_name])
            ;

            return response($leads);
        }
        else if($request->column == 'assistant'){
            $spielgruppens = $playgroupQuery
                    ->pluck('id')
                    ->toArray()
                ;

            $assistantId = OpeningHour::query()
                    ->select('assistant_id')
                    ->whereIn('spielgruppen_id', $spielgruppens)
                    ->distinct()
                    ->pluck('assistant_id')
                    ->toArray()
                    ;

            $assistants = User::query()
                        ->select(['id', 'first_name', 'last_name'])
                        ->whereIn('id', $assistantId)
                    ->get()
                    ->map(fn($item)=> ['id'=>$item->id, 'name'=> $item->first_name.' '.$item->last_name])
            ;

            return response($assistants);
        }
        else if($request->column == 'child'){
            $spielgruppens =  $playgroupQuery
                ->pluck('id')
                ->toArray()
                ;

            $childrens = ChildrenRegistration::query()
                    ->select(['id', 'child_first_name', 'child_last_name'])
                    ->whereHas('childrenRegistrationOpeningHours', function($q) use($spielgruppens){
                        $q->whereIn('spielgruppen_id', $spielgruppens)
                        ;
                    })
                    ->get()
                    ->map(fn($item)=> ['id'=>$item->id, 'name'=> $item->child_first_name.' '.$item->child_last_name])
                    ;

            return response($childrens);
        }
        else if($request->column == 'marking'){
            $spielgruppens = $playgroupQuery
            ->pluck('id')
            ->toArray()
            ;

            $markes = ChildrenRegistration::query()
                ->select('marked')
                // ->whereIn('spielgruppen_id', $spielgruppens)
                ->whereHas('childrenRegistrationOpeningHours', function($q) use($spielgruppens){
                    $q->whereIn('spielgruppen_id', $spielgruppens)
                    ;
                })
                ->distinct()
                ->get()
                ->map(fn($item)=> ['name'=>$item->marked == 1 ? 'Markierte' : 'Unmarkierte', 'id'=>$item->marked])
                // ->pluck('marked')
                // ->toArray()
                ;
            return $markes;
        }

        else if($request->column == 'classification'){
            $activeDivision = Division::query()
            ->where('company_id', \auth()->user()->company_id)
            // ->where('status_id', 2)
            ->when(request('classification'), function($q){
                $q->where('id', request('classification'));
            },function($q){
                $q
                ->where('status_id', 2)
                ;
            })
            ->first()
            ;
            // if($activeDivision) {
            //     $q->where('id', $activeDivision->id);
            // }else {
            //     $q->where('status_id', 1);
            // }

            $divisions = Division::query()
                    ->select(['id', 'name', 'status_id'])
                    ->where('company_id', Auth::user()->company_id)
                ->get()
            ;

            return response($divisions);
        }



    }
}
