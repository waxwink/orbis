<?php

if (! function_exists('removeDuplicateSlashes')) {
    function removeDuplicateSlashes(string $file): string
    {
        return (string)str_replace("//", "/", $file);
    }
}
