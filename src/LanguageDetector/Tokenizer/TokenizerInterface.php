<?php

declare(strict_types = 1);

namespace LanguageDetector\Tokenizer;

/**
 * Interface TokenizerInterface
 *
 * @copyright 2019 Mohamed-yassine BELATAR
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Mohamed-yassine BELATAR <e98@hotmail.fr>
 * @package LanguageDetector
 */

interface TokenizerInterface
{
    public function tokenize(string $str): array;
}