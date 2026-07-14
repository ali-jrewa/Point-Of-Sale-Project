<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyAccountController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account = User::find(Auth::user()->id)->load('role');
        return view('account.list', compact('account'));
    }


    public function edit(User $account)
    {
        $account->load('role');

        return response()->json($account);
    }

    public function update(UpdateAccountRequest $request, User $account)
    {

        $validated = $request->validated();

        if ($request->hasFile('account_image')) {

            // Delete the old image if it exists to clean up server space
        if ($account->account_image) {
            Storage::disk('public')->delete($account->account_image);
        }

        // Store the new image in the 'storage/app/public/accounts' folder
        $path = $request->file('account_image')->store('accounts', 'public');

        // Save the new path into the validated data array
        $validated['account_image'] = $path;
        }

        // 3. Handle the Password (only update it if a new one was typed)
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // Don't overwrite existing password with null
        }

            $account->update($validated);

            return response()->json([
            'success' => 'Account updated successfully.',
            'account' => $account
        ]);
    }
}
