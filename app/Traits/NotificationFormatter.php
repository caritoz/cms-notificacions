<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Date;
    use Illuminate\Support\Str;

trait NotificationFormatter
{
    /**
     * @param $content
     * @return array[]
     */
    public function setData($content): array
    {
        return [
            'message' => [
                'content' => Str::limit($content, 80),
                'slug' => $this->getEntity()->slug,
                'user' => Auth::user()->only([
                    'id',
                    'email',
                    'name',
                ])
            ]
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function setContent($notifiable): array
    {
        //silly!!! but the notification doesnt exists yet and needs to keep the Notification schema for UI
        $data = [];
        $data['created_at'] = Date::now();
        $data['read_at'] = null;
        $data['data'] = $this->toArray($notifiable);

        return $data;
    }
}
