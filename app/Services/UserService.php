<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class  UserService
{
     public function getPaginatedLinks()
    {
        return User::whereIn('role_id', [3,2])->with('role')->latest()->withTrashed()->paginate(10);
    }

    public function search(?string $search)
    {
        return User::with('role')
            ->whereIn('role_id', [2, 3])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('display_name', 'like', "%{$search}%");
                    });


                    $status = UserStatus::tryFrom(strtolower(trim($search)));
                    if ($status !== null || $status != "") {
                        $q->orWhere('status', $status->value);
                    }
                });
            })
            ->latest()
            ->get();
    }

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {

            return User::create($data);

        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (empty($data['password'])) {
            unset($data['password']);
            }

            $user->update($data);

            return $user;

                });
            }

    public function delete(User $user): void
    {
        $user->delete();
    }

}
