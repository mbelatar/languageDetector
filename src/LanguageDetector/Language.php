<?php

declare(strict_types = 1);

namespace LanguageDetector;

/**
 * Class Language
 *
 * @copyright 2019 Mohamed-yassine BELATAR
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Mohamed-yassine BELATAR <e98@hotmail.fr>
 * @package LanguageDetector
 */
class Language extends NgramParser
{
  /**
   * @var array
   */
  protected $tokens = [];
  protected $language = [];
  protected $language_code = []; // ISO 639-1

  /**
   * Loads all language files
   *
   * @param array $lang List of ISO 639-1 codes, that should be used in the detection phase
   * @param string $dirname Name of the directory where the translations files are located
   */
  public function __construct(array $lang = [], string $dirname = '')
  {
    if (empty($dirname))
    {
      $dirname = __DIR__ . '/../../resources/*/*.json';
    }
    else if (!is_dir($dirname) || !is_readable($dirname))
    {
      throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
    }
    else
    {
      $dirname = rtrim($dirname, '/');
      $dirname .= '/*/*.json';
    }
    $isEmpty = empty($lang);
    // $dirname = realpath($dirname);
    foreach (glob($dirname) as $json)
    {
      // echo $json."\n";
      if ($isEmpty || in_array(basename($json, '.json'), $lang))
      {
        $this->tokens += json_decode(file_get_contents($json), true);
        array_push($this->language,basename(dirname($json)));
        array_push($this->language_code,basename($json, '.json'));
      }
    }
    // $this->language_code = array_keys($this->tokens); //ISO 639-1
    // print_r($this->tokens);
    // die();
  }
  /**
   * Detects the language from a given text string
   *
   * @param string $str
   * @return LanguageResult
   */
  public function detect(string $str): LanguageResult
  {
    $str = mb_strtolower($str);
    $samples = $this->getNgrams($str);
    // print_r($samples);
    $result = [];
    // print_r($this->tokens);
    if (count($samples) > 0)
    {
      foreach ($this->tokens as $lang => $value)
      {
        $index = $sum = 0;
        $value = array_flip($value);
        foreach ($samples as $v)
        {
          if (isset($value[$v]))
          {
            $x = $index++ - $value[$v];
            $y = $x >> (PHP_INT_SIZE * 8);
            $sum += ($x + $y) ^ $y;
            continue;
          }
          $sum += $this->maxNgrams;
          ++$index;
        }
        $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
      }
      arsort($result, SORT_NUMERIC);
    }
    // print_r($result);
    return new LanguageResult($result);
  }
  /**
   * Detects the language from a given text string
   *
   * @param string $str
   * @return LanguageResult
   */
  public function simpledetect(string $str): LanguageResult
  {
    $str = mb_strtolower($str);
    $samples = $this->getNgrams($str);
    // print_r($samples);
    $result = [];
    // print_r($this->tokens);
    if (count($samples) > 0)
    {
      foreach ($this->tokens as $lang => $value)
      {
        $index = $sum = 0;
        $value = array_flip($value);
        foreach ($samples as $v)
        {
          if (isset($value[$v]))
          {
            $x = $index++ - $value[$v];
            $y = $x >> (PHP_INT_SIZE * 8);
            $sum += ($x + $y) ^ $y;
            continue;
          }
          $sum += $this->maxNgrams;
          ++$index;
        }
        $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
      }
      arsort($result, SORT_NUMERIC);
    }
    // print_r($result);
    return new LanguageResult($result);
  }
  public function listLanguages()
  {
    return $this->language;
  }
  public function listLanguageCodes()
  {
    return $this->language_code;
  }
}
