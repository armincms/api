<?php

namespace Armincms\Api\Http\Controllers;

use Armincms\Api\Http\Requests\ProfileUpdateRequest;

class ProfileUpdateController extends Controller
{
    /**
     * Update the user profile
     *
     * @return array
     */
    public function handle(ProfileUpdateRequest $request)
    {
        $request->updateUserProfile();

        return [
            'status' => 'success',
            'message' => __('Your profile is up to date.'),
        ];
    }
}
