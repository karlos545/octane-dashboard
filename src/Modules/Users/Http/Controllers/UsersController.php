<?php

namespace Octane\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Octane\Modules\Users\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->has('q')) {
            $users = $this->searchUsers($users, explode(' ', $request->get('q')));
        }

        $users = $users->paginate();

        return view('octane::modules.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();

        return view('octane::modules.users.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();

        return view('octane::modules.users.edit', compact('roles', 'user'));
    }

    public function destroy(User $user)
    {
        if (! request()->user()->can('delete users')) {
            return back()->withErrors("You cannot deactivate this user.");
        }

        $user->delete();

        return back()->withSuccess("User with the email [{$user->email}] has been deactivated.");
    }

    protected function searchUsers($users, $terms)
    {
        foreach ($terms as $term) {
            $users
                ->orWhere('email', 'LIKE', "{$term}%")
                ->orWhere('first_name', 'LIKE', "{$term}%")
                ->orWhere('last_name', 'LIKE', "{$term}%");
        }

        return $users;
    }
}
