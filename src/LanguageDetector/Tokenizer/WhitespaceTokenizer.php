<?php

declare(strict_types = 1);

namespace LanguageDetector\Tokenizer;

/**
* Class WhitespaceTokenizer
*
* @copyright 2019 Mohamed-yassine BELATAR
* @license https://opensource.org/licenses/mit-license.html MIT
* @author Mohamed-yassine BELATAR <e98@hotmail.fr>
* @package LanguageDetector
*/
class WhitespaceTokenizer implements TokenizerInterface
{
  /**
  * @param string $str
  * @return array
  */
  public function tokenize(string $str): array
  {
    $splited = preg_split('/[^\pL]+(?<![\x27\x60\x{2019}])/u', $str, -1, PREG_SPLIT_NO_EMPTY);
    return array_map(
      function ($word) {
        return "_{$word}_";
      },
      $splited
    );
  }
}