<?php Namespace WordPress\Plugin\Encyclopedia;

Use DOMDocument, DOMXPath;

class Cross_Linker {
  private
    $DOM = False,
    $XPath = False,
    $skip_elements = Array(),
    $link_complete_words_only = False,
    $replace_phrases_once = False,
    $link_target = '_self',
    $escape_tags = Array('script', 'style', 'code', 'pre'), # These tags will not be loaded inside the PHP DOMDocument object
    $cache_expression = '{STRINGCACHE:%s}',
    $content_wrapper = 'content-wrapper',
    $data_cache = Array();

  public function loadContent($content){
    $encoded_content = MB_Convert_Encoding($content, 'HTML-ENTITIES', 'UTF-8');
    $encoded_content = $this->escapeTags($this->escape_tags, $encoded_content);
    $encoded_content = "<{$this->content_wrapper}>{$encoded_content}</{$this->content_wrapper}>";

    $this->DOM = new DOMDocument();
    if (!@$this->DOM->loadHTML($encoded_content)) return False; # Here we could get a Warning if the $content is not valid HTML
    $this->XPath = new DOMXPath($this->DOM);

    return True;
  }

  private function escapeTags($tags, $content){
    if (!is_Array($tags)) return $content;
    foreach ($tags as $tag){
      $regex = sprintf('%%(<%1$s\b[^>]*>)(.*)(</%1$s>)%%imsuU', $tag);
      $content = PReg_Replace_Callback($regex, Array($this, 'cacheMatch'), $content);
    }
    return $content;
  }

  private function cacheMatch($match){
    $string = $match[2];
    $key = 'MD5:'.MD5($string);
    $this->data_cache[$key] = $string;
    return $match[1] . sprintf($this->cache_expression, $key) . $match[3];
  }

  private function uncacheStrings($content){
    $this->data_cache = Array_Reverse($this->data_cache);
    foreach ($this->data_cache as $key => $string){
      $content = Str_Replace(sprintf($this->cache_expression, $key), $string, $content);
    }
    return $content;
  }

  public function setSkipElements($elements){
    $elements = is_Array($elements) ? $elements : Array();
    $this->skip_elements = $elements;
  }

  public function linkCompleteWordsOnly($state = True){
    $this->link_complete_words_only = (Boolean) $state;
  }

  public function replacePhrasesOnce($state = True){
    $this->replace_phrases_once = (Boolean) $state;
  }

  public function setLinkTarget($target){
    $this->link_target = $target;
  }

  public function linkPhrase($phrase, $callback, $callback_arg = null){
    # Check if there is a valid XPath object available
    if (!$this->XPath) return False;

    # Prepare search term
    $phrase = trim($phrase);
    $phrase = WPTexturize($phrase); # This is necessary because the content runs through this filter, too
    $phrase = HTML_Entity_Decode($phrase, ENT_QUOTES, 'UTF-8');
    $phrase = HTMLSpecialChars($phrase);
    $phrase = PReg_Quote($phrase, '/');

    # Prepare search
    $word_boundary = '^|\W|$';
    $pattern_modifiers = 'imsuU';
    $search_regex = $this->link_complete_words_only ? sprintf('/(%1$s)(%%s)(%1$s)/%2$s', $word_boundary, $pattern_modifiers) : sprintf('/(%1$s)(%%s)/%2$s', $word_boundary, $pattern_modifiers);
    $search = sprintf($search_regex, $phrase);
    $item = null;
    $link = null;

    # Build XPath to find all text elements and skip non-text elements like images and videos
    $xpath_query = '//text()[not(ancestor::*[contains(@class,"no-cross-linking")])]';
    foreach ($this->skip_elements as $skip_element){
      $xpath_query .= sprintf('[not(ancestor::%s)]', $skip_element);
    }

    # Go through nodes and replace
    foreach ($this->XPath->query($xpath_query) as $original_node){
      $original_text = HTMLSpecialChars(HTML_Entity_Decode($original_node->wholeText, ENT_QUOTES, 'UTF-8'));
      if (PReg_Match($search, $original_text)){
        if (empty($item) && is_Callable($callback)){
          $item = $callback($callback_arg);
          $link = sprintf('$1<a href="%1$s" target="%2$s" title="%3$s" class="encyclopedia">$2</a>$3', $item->url, $this->link_target, esc_Attr(HTMLSpecialChars($item->title)));
          $link = apply_Filters('encyclopedia_cross_link_element', $link, $item->url, $this->link_target, $item->title);
        }

        $new_text = @PReg_Replace($search, $link, $original_text, ($this->replace_phrases_once ? 1 : -1)); # This could break if your terms contains very special characters which break the search regex

        $this->setNodeContent($original_node, $new_text);

        if ($this->replace_phrases_once) break; # We only replace the first match of this term with a link
      }
    }
  }

  private function setNodeContent($node, $new_html){
    $new_node = $this->DOM->createDocumentFragment();
    if (@$new_node->appendXML($new_html)){ # If the $new_html is not valid XML this will break
      $node->parentNode->replaceChild($new_node, $node);
    }
  }

  public function getParserDocument(){
    if (!$this->DOM) return False;
    $resultHTML = $this->DOM->saveHTML();

    $head_start = '<head>';
    $head_start_pos = MB_StrPos($resultHTML, $head_start, 0, 'UTF-8');
    $head_end = '</head>';
    $head_end_pos = MB_StrPos($resultHTML, $head_end, $head_start_pos + StrLen($head_start), 'UTF-8');
    $head = ($head_start_pos && $head_end_pos) ? MB_SubStr($resultHTML, $head_start_pos + StrLen($head_start), $head_end_pos - $head_start_pos - StrLen($head_start)) : '';

    $body_start = "<body><{$this->content_wrapper}>";
    $body_start_pos = MB_StrPos($resultHTML, $body_start, 0, 'UTF-8');
    $body_end = "</{$this->content_wrapper}></body>";
    $body_end_pos = MB_StrPos($resultHTML, $body_end, $body_start_pos + StrLen($body_start), 'UTF-8');
    $body = ($body_start_pos && $body_end_pos) ? MB_SubStr($resultHTML, $body_start_pos + StrLen($body_start), $body_end_pos - $body_start_pos - StrLen($body_start)) : '';

    $html = $this->uncacheStrings($head . $body);
    return $html;
  }

}
