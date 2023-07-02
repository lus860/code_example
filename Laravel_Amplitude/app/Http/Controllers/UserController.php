<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAmpEvent;

class UserController extends AccountController
{
    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function add(Request $request)
    {
        $user = User::createUser($request, $this->company->id);

        if ($user) {
            $amplitudeEventProperties = [
                'company_name' => $this->company->name,
                'company_url' => $this->company->URL_DOMAIN(),
                'new_user_name' => $user->getName(),
                'new_user_email' => $user->email,
                'new_user_id' => $user->id,
                'new_user_role' => $user->_getRole()->name,
                'creator_name' => $this->user->getName(),
                'creator_email' => $this->user->email,
                'creator_role' => $this->user->_getRole()->name,
                'user_created_date' => $user->created_at,
            ];

            ProcessAmpEvent::dispatch($this->user, $this->company, ProcessAmpEvent::EVENT_NEW_USER, $amplitudeEventProperties, $_SERVER['REMOTE_ADDR'], $this->browserAgent)->onQueue(ProcessAmpEvent::QUEUE_NAME);

            return response()->json([
                'success' => true,
                'users' => $user,
            ], Response::HTTP_CREATED);
        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

    /**
     * @param User $user
     * @param UserEditRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UserEditRequest $request)
    {
        $amplitudeEventProperties['old_user_info'] = $user->only(['first_name', 'last_name', 'email', 'id_apm_acl_role']);
        $user = User::updateUser($user, $request);
        if ($user) {
            $amplitudeEventProperties['new_user_info'] = $user->only(['first_name', 'last_name', 'email', 'id_apm_acl_role']);
            ProcessAmpEvent::dispatch($this->user, $this->company, ProcessAmpEvent::EVENT_UPDATE_USER, $amplitudeEventProperties, $_SERVER['REMOTE_ADDR'], $this->browserAgent)->onQueue(ProcessAmpEvent::QUEUE_NAME);

            return response()->json([
                'success' => true,
                'users' => $user,
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user)
    {
        try {
            if ($user->delete()) {
                $amplitudeEventProperties = [
                    'deleted_user_name' => $user->getName(),
                    'deleted_user_id' => $user->id,
                    'deleted_user_email' => $user->email
                ];
                ProcessAmpEvent::dispatch($this->user, $this->company, ProcessAmpEvent::EVENT_DELETE_USER, $amplitudeEventProperties, $_SERVER['REMOTE_ADDR'], $this->browserAgent)->onQueue(ProcessAmpEvent::QUEUE_NAME);
            }

            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }
    }

}
