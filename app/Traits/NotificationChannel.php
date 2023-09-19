<?php

    namespace App\Traits;

    use Illuminate\Support\Arr;

trait NotificationChannel
{
    /**
     * @param $class
     * @return array|string
     */
    public function viaSettings($class): array|string
    {
        $channels = $this->notificationSettings()
            ->whereHas('notificationTypes', function ($query) use ($class) {
                $query->where('class', '=', $class);
            })
            ->pluck('channel')
            ->toArray();

        if ($channels) {
            $channels =  Arr::flatten($channels); // set a condition: if only chose a "mail" dont set in "database"
            if (in_array('broadcast', $channels)) {
                $channels[] = 'database';
            }

            return $channels;
        }

        return '';
    }
}
