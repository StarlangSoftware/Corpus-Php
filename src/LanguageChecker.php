<?php

namespace olcaytaner\Corpus;

abstract class LanguageChecker
{
    abstract public function isValidWord(string $word): bool;
}
