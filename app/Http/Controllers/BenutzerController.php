<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\BenutzerMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\BenutzerRequest;
use App\Http\Resources\BenutzerResource;
use App\Http\Requests\UpdateBenutzerReqest;
use App\Models\Role;
use App\Models\Status;
use App\Notifications\UserInvitationNotification;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class BenutzerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        // return 'hello';

        //Old code
        // $benutzers = User::playgroups()->byCompany()->paginate(5);
        // return BenutzerResource::collection($benutzers);

        $benutzers = User::query()
            ->playgroups()
            ->byCompany()
            ->where(function ($q) {
                $q->when(request('role_id'), function ($q) {
                    $roles = explode(',', request('role_id'));
                    $q->whereIn('role_id', $roles);
                })
                    ->when(request('status_id'), function ($q) {
                        $roles = explode(',', request('status_id'));
                        $q->whereIn('status_id', $roles);
                    });
            })
            ->where(function ($q) {
                $q->when(request('query'), function ($q) {
                    $query = request('query');
                    $q
                        ->where('first_name', 'LIKE', "$query%")
                        ->orWhere('last_name', 'LIKE', "$query%")
                        ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", [$query . '%']);
                });
            })
            ->when(request('order_by'), function ($q) {
                if (in_array(request('order_by'), ['first_name', 'role_id', 'email', 'phone_number', 'status_id'])) {
                    $q->orderBy(request('order_by'), request('direction', 'DESC'));
                }
            }, fn ($q) => $q->orderBy('id', 'DESC'))
            ->paginate(request('per_page', 25))
            ->withQueryString();


        return BenutzerResource::collection($benutzers);
    }
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function filter()
    {
        $benutzers = User::playgroups()->byCompany()->get();
        return BenutzerResource::collection($benutzers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BenutzerRequest $request
     * @return JsonResponse
     */
    public function store(BenutzerRequest $request)
    {
        try {
            $password = uniqid();
            $benutzer = User::create([
                'image' => $request->hasFile('image') ? $request->file('image')->store('benutzer') : null,
                'role_id' => $request->role_id,
                'status_id' => 3, // pending
                'street_name' => $request->street_name,
                'house_number' => $request->house_number,
                'postal_code' => $request->postal_code,
                'location' => $request->location,
                'email' => $request->email,
                'prefix' => "+$request->prefix",
                'phone_number' => $request->phone_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => bcrypt($password),
                'company_id' => Auth::user()->company_id,
                'parent_admin_id' => Auth::id(),
                'prefix_id' =>  $this->getLastUserId(),
            ]);

            Mail::to($request->email)->send(new BenutzerMail($benutzer, $password, Auth::user()));

            return response()->json('Benutzer erfolgreich erstellt');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }

    public function show(User $benutzer)
    {
        return new BenutzerResource($benutzer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBenutzerReqest $request
     * @param User $benutzer
     * @return BenutzerResource|JsonResponse
     */
    public function update(UpdateBenutzerReqest $request, User $benutzer)
    {
        try {
            if ($request->hasFile('image') && $benutzer->image) {
                Storage::delete($benutzer->image);
            }

            $benutzer->update([
                'image' => $request->hasFile('image') ? $request->file('image')->store('benutzer') : $benutzer->image,
                'role_id' => $request->role_id,
                'street_name' => $request->street_name,
                'house_number' => $request->house_number,
                'postal_code' => $request->postal_code,
                'location' => $request->location,
                'email' => $request->email,
                'prefix' => "+$request->prefix",
                'phone_number' => $request->phone_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            return new BenutzerResource($benutzer);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }

    /**
     * Update the specified resource from storage.
     *
     * @param User $benutzer
     * @return JsonResponse
     */
    public function status(\Illuminate\Http\Request $request, User $benutzer)
    {
        $benutzer->update([
            'status_id' => $request->status_id
        ]);

        return response()->json('Benutzer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $benutzer
     * @return JsonResponse
     */
    public function destroy(User $benutzer)
    {
        $benutzer->delete();
        return response()->json('Benutzer deleted successfully');
    }

    public function sendInvitation(User $benutzer): JsonResponse
    {
        //old coce
        // try {
        //     $password = uniqid();
        //     $benutzer->update([
        //         'password' => bcrypt($password),
        //     ]);
        //     Mail::to($benutzer->email)->send(new BenutzerMail($benutzer, $password));
        //     return response()->json('Invitation sent successfully');
        // } catch (\Exception $exception) {
        //     Log::info($exception->getMessage());
        //     return response()->json('Failed to send invitation', 500);
        // }


        try {
            $password = uniqid();
            $benutzer->update([
                'password' => bcrypt($password),
                // 'status_id' => 1
            ]);
            $benutzer->notify(new UserInvitationNotification($password));
            return response()->json('Invitation sent successfully');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Failed to send invitation', 500);
        }
    }

    public function getLastUserId()
    {
        $last_registration = User::where('company_id', Auth::user()->company_id)
        ->where(fn($q) => $q->where('role_id', 3)->orWhere('role_id', 2))
        ->orderBy('id', 'desc')
        ->count();

        $id =  $last_registration > 0 ? $last_registration + 1 : 1;

        return sprintf("%s%05d", 'BN', $id);
    }

    public function deleteImage(User $benutzer)
    {
        Storage::delete($benutzer->image);
        $benutzer->image = null;
        $benutzer->save();

        return response()->json('Image has been deleted', 200);
    }

    public function getFilterData(Request $request)
    {
        $query = User::query()
            ->playgroups()
            ->byCompany()
            ->where(function ($q) {
                $q->when(request('role_id'), function ($q) {
                    $roles = explode(',', request('role_id'));
                    $q->whereIn('role_id', $roles);
                })
                    ->when(request('status_id'), function ($q) {
                        $roles = explode(',', request('status_id'));
                        $q->whereIn('status_id', $roles);
                    });
            })
            ->where(function ($q) {
                $q->when(request('query'), function ($q) {
                    $query = request('query');
                    $q
                        ->where('first_name', 'LIKE', "$query%")
                        ->orWhere('last_name', 'LIKE', "$query%")
                        ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", [$query . '%']);
                });
            })
            ->when(request('order_by'), function ($q) {
                if (in_array(request('order_by'), ['first_name', 'role_id', 'email', 'phone_number', 'status_id'])) {
                    $q->orderBy(request('order_by'), request('direction', 'DESC'));
                }
            }, fn ($q) => $q->orderBy('id', 'DESC'))


            ->when(request('column' == 'role_id'), function ($q) {
                $q->select('role_id',);
            })
            ->when(request('column' == 'status_id'), function ($q) {
                $q->select('status_id');
            });

        if ($request->column == 'role_id') {
            $roles_id = $query->pluck('role_id')->toArray();

            $result = Role::select('id', 'name')
                ->whereIn('id', $roles_id)
                ->orderByRaw("field(id," . implode(',', $roles_id) . ")")
                ->get()
                ->map(function($item){
                    return [
                        'id'    =>  $item->id,
                        'name'  =>  User::getUserByRole($item->id)
                    ];
                })
                ;

            return response($result);
        } else if ($request->column == 'status_id') {
            $status_id = $query->pluck('status_id')->toArray();
            $result = Status::select('id', 'name')
                ->whereIn('id', $status_id)
                ->orderByRaw("field(id," . implode(',', $status_id) . ")")
                ->get();

            return response($result);
        }
    }
}
