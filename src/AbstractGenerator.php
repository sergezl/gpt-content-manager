<?php
namespace SZ;
abstract class AbstractGenerator
{
    /**
     * Generate a text based on a combined prompt.
     *
     * @param string $prompt The final prompt (general + specific).
     * @return string
     * @throws \Exception
     */
    abstract public function generate(string $prompt): string;
}