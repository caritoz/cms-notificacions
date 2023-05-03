<?php

namespace App\Http\Controllers;

use App\Models\NotificationSettings;
use App\Models\NotificationTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class NotificationSettingsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation
        Validator::make($request->all(), [
            '*.notification_types_id'   =>  ['required', 'integer', Rule::in( NotificationTypes::pluck('id')->all() )],
            '*.channel'                    =>  ['required', 'array', 'min: 1'],
            '*.channel.*'                  =>  ['string', Rule::in(NotificationTypes::TYPE_CHANNELS)],
        ])->validate();

        // because eloquent has problems with composite relationships
        $user->notificationSettings()->delete();

        foreach ($request->all() as $notificationSetting) {
            $user->notificationSettings()->create( $notificationSetting );
        }

        return $user->notificationSettings;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NotificationSettings $notificationSettings
     * @return NotificationSettings
     */
    public function destroy(NotificationSettings $notificationSettings)
    {
        $notificationSettings->delete();

        return $notificationSettings;
    }
}
