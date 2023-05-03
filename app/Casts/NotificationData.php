<?php

    namespace App\Casts;

    use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
    use Illuminate\Database\Eloquent\Casts\ArrayObject;

    class NotificationData implements CastsAttributes
    {
        /**
         * Cast the given value.
         *
         * @param  \Illuminate\Database\Eloquent\Model  $model
         * @param  string  $key
         * @param  mixed  $value
         * @param  array  $attributes
         * @return mixed
         */
        public function get($model, string $key, $value, array $attributes)
        {
            return isset($attributes[$key]) ? $this->refreshDataUser($key,  $attributes) : null;
        }

        /**
         * Prepare the given value for storage.
         *
         * @param  \Illuminate\Database\Eloquent\Model  $model
         * @param  string  $key
         * @param  mixed  $value
         * @param  array  $attributes
         * @return mixed
         */
        public function set($model, string $key, $value, array $attributes)
        {
            return [$key => json_encode($value)];
        }

        /**
         * @param $key
         * @param $attributes
         * @return array|ArrayObject
         */
        private function refreshDataUser(string $key,  array $attributes): ArrayObject|array
        {
            $data = new ArrayObject(json_decode($attributes[$key], true));

            $data['message']['user']['avatar'] = \App\Models\User::find($data['message']['user']['id'])->avatar; // because full path has expiration date

            return $data;
        }
    }
