<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = $this->userService->getPaginatedLinks();
        $roles = Role::all();
        return view('user.list', compact('users' , 'roles'));
    }

    public function getUsers(Request $request)
    {
        $request->validate([
            'search' => 'string|max:50|nullable'
        ]);

        $users = $this->userService
            ->search($request->search);

        return response()->json($users);
    }


    public function store(StoreUserRequest $request)
    {
        $this->userService->store($request->validated());

        return response()->json(['success' => 'User created successfully.'], 201);
    }

    public function edit(User $user)
    {
        $user->load('role');

    return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
    $this->userService->update($user, $request->validated());

    return response()->json([
        'success' => 'User updated successfully.'
    ]);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return response()->json([
            'success' => 'User deleted successfully.'
        ]);
    }
}
