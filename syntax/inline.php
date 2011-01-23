<?php
/**
 * <text> tag that embeds plain text with linebreaks
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_plaintext_inline extends DokuWiki_Syntax_Plugin {

    function getType() { return 'protected';}
    function getPType() { return 'normal';}
    function getSort() { return 20; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<text>.*?</text>', $mode, 'plugin_plaintext_inline');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        return substr($match,6,-7);
    }

    /**
     * Create output
     */
    function render($format, &$renderer, $data) {
        if($format == 'xhtml'){
            $renderer->doc .= str_replace( "\n", "<br/>", trim($renderer->_xmlEntities($data),"\n") );
            return true;
        }else if($format == 'metadata'){
            $renderer->doc .= trim($data);
            return true;
        }
        return false;
    }
}
