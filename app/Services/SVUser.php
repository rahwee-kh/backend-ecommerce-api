<?php
namespace App\Services;

use App\Models\Api\User;
use App\Services\BaseService;
use App\Http\Tools\ParamTools;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;



class SVUser extends BaseService
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($params)
    {
        $perPage       = ParamTools::get_value($params, 'per_page', 10);
        $sortField     = ParamTools::get_value($params, 'sort_field', 'updated_at');
        $sortDirection = ParamTools::get_value($params, 'sort_direction', 'desc');

        $query = User::query()
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return UserResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($params)
    {
        $data = $params;
        $data['is_admin'] = true;
        $data['email_verified_at'] = date('Y-m-d H:i:s');
        $data['password'] = Hash::make($data['password']);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update($params, User $user)
    {
        $data = $params;

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $data['updated_by'] = auth()->user()->id;

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        if(auth()->user()->id === $user->id){
            throw new \Exception("You can't delete your own user account.");
        }
        $user->delete();
        return response()->noContent();
    }
}