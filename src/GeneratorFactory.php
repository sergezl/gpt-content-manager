<?php
namespace SZ;

use SZ\AbstractGenerator;
use SZ\OpenAiGenerator;

class GeneratorFactory
{
    /**
     * Returns a specific generator.
     * Later, you can add new types (Gemini, Claude, etc.) without changing the REST.
     */
    public static function make(): AbstractGenerator
    {
        // So far, only OpenAI
        return new OpenAiGenerator();
    }
}