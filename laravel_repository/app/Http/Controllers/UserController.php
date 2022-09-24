<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends AccountController
{
    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function add(UserRequest $request)
    {
        $user = User::createUser($request, $this->company->id);

        if (!$user) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * @param User $user
     * @param UserEditRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UserEditRequest $request)
    {
        $user = User::updateUser($user, $request);
        if (!$user) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function users()
    {
        $users = $this->userRepository->getUsers($this->company->id);

        if (!$users)  {
            return self::httpBadRequest(self::NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'users' => $users
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $this->userRepository->getUserById($request->id, $this->company->id);

        if (!$user)  {
            return self::httpBadRequest(self::NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ], Response::HTTP_OK);
    }

}
