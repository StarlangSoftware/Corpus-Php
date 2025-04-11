<?php

namespace olcaytaner\Corpus;

enum WordFormat
{
    /**
     * Surface/Original form
     */
    case SURFACE;
    /**
     * Create 2-Gram words as output.
     */
    case LETTER_2;
    /**
     * Create 3-Gram words as output.
     */
    case LETTER_3;
    /**
     * Create 4-Gram words as output.
     */
    case LETTER_4;
}