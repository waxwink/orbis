<?php

if (! function_exists('preparePath')) {
    function removeDuplicateSlashes(string $file): string
    {
        return (string)str_replace("//", "/", $file);
    }
}
