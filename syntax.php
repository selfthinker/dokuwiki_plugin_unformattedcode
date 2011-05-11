<?php
/**
 * UnformattedCode Plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Anika Henke <anika@selfthinker.org>
 */

if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_unformattedcode extends DokuWiki_Syntax_Plugin {

    function getType() {
        return 'protected';
    }
    function getAllowedTypes() {
        return array('formatting', 'disabled');
    }
    function getPType() {
        return 'normal';
    }
    function getSort() {
        return 99; // one less than core 'monospace', so it overwrites it
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('\x27\x27(?=.*\x27\x27)',$mode,'plugin_unformattedcode');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('\x27\x27', 'plugin_unformattedcode');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
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
    function render($mode, &$renderer, $indata) {

        if (empty($indata)) return false;
        list($state, $data) = $indata;

        if($mode == 'xhtml'){
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $renderer->doc .= '<code>';
                    break;

                case DOKU_LEXER_EXIT:
                    $renderer->doc .= "</code>";
                    break;
            }
            return true;
        }
        return false;
    }

}
