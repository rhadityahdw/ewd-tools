<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Division;
use App\Services\UserService;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService)
    {}

    public function index()
    {
        $users = User::with(['roles', 'division'])->get();
        
        return Inertia::render('users/Index', [
            'users' => UserResource::collection($users)
        ]);
    }

    public function create()
    {
        return Inertia::render('users/Create', [
            'roles' => Role::all(),
            'divisions' => Division::all()
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return Inertia::render('users/Show', [
            'user' => new UserResource($user->load(['roles', 'division']))
        ]);
    }

    public function edit(User $user)
    {        
        return Inertia::render('users/Edit', [
            'user' => new UserResource($user),
            'roles' => Role::all(),
            'divisions' => Division::all()
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
