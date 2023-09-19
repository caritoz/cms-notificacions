<?php

    namespace App\Traits;

trait HasMentions
{
    public function getMentions(): array
    {
        $mentions = [];
        if (preg_match_all('/data-id=\"([0-9]+)\"/', $this->body, $output_array)) {
            if (isset($output_array[1])) {
                $mentions = $output_array[1];
            }
        }

        return $mentions;
    }
}
