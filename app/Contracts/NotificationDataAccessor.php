<?php

    namespace App\Contracts;

    use App\Models\Post;

    interface NotificationDataAccessor
    {
        /**
         * @return mixed
         */
        public function getEntity(): Post;
    }
