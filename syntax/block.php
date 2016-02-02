<?php
/**
 * <TEXT> tag that embeds plain text with auto linebreaks in <pre>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_plaintext_block extends DokuWiki_Syntax_Plugin {

    function getType() { return 'protected'; }
    function getPType() { return 'block'; }
    function getAllowedTypes() { return array(); }
    function getSort() { return 20; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<TEXT>(?=.*?</TEXT>)',$mode,'plugin_plaintext_block');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</TEXT>','plugin_plaintext_block');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return array($state);
            case DOKU_LEXER_UNMATCHED :
                $handler->_addCall('cdata', array($match), $pos);
                return false;
            case DOKU_LEXER_EXIT :
                return array($state);
        }
        return false;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        if($format == 'xhtml'){
            list($state) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $renderer->doc .= '<pre class="code plaintext">';
                    break;
                case DOKU_LEXER_EXIT:
                    $renderer->doc .= "</pre>";
                    break;
            }
            return true;
        }else if($format == 'metadata'){
            // do nothing since content is managed in "unmatched" state
            return true;
        }
        return false;
    }
}
