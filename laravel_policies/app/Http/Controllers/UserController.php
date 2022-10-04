<?php

namespace App\Http\Controllers;

use App\Http\StatusCode\HTTPStatusCode;
use App\Models\User;
use Illuminate\Http\Request;
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
            'count_total' => $this->userRepository->userTotalCount($this->company->id)
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
            'count_total' => $this->userRepository->userTotalCount($this->company->id)
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
            'users' => $users,
            'count_total' => $this->userRepository->userTotalCount($this->company->id)
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
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function getUsers($id_company, $is_mobile = null)
    {
        $users = $this->userRepository->getUserForTeam($id_company, $is_mobile);

        foreach ($users as $user) {
            $user->getName("{f} {l}");
            $user->getAvatar();
            $user->getRole();
        }

        return $users;
    }

    public function usersForTeam(Request $request)
    {
        return response()->json([
            'success' => true,
            'users' => $this->getUsers($this->company->id, $request->is_mobile),
            'count_total' => $this->userRepository->userTotalCount($this->company->id)
        ], HTTPStatusCode::OK);
    }
}
