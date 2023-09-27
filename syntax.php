<?php
/**
 * UnformattedCode Plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Anika Henke <anika@selfthinker.org>
 */


use dokuwiki\Extension\SyntaxPlugin;

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
        $this->Lexer->addEntryPattern('\x22\x22(?=.*\x22\x22)',$mode,'plugin_unformattedcode'); // ""code""
        if ($this->getConf('overwrite')) {
            $this->Lexer->addEntryPattern('\x27\x27(?=.*\x27\x27)',$mode,'plugin_unformattedcode'); // ''code''
        }
    }
    function postConnect() {
        $this->Lexer->addExitPattern('\x22\x22', 'plugin_unformattedcode');
        if ($this->getConf('overwrite')) {
            $this->Lexer->addExitPattern('\x27\x27', 'plugin_unformattedcode');
        }
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return array($state);

            case DOKU_LEXER_UNMATCHED :
                $handler->addCall('cdata', array($match), $pos);
                return false;

            case DOKU_LEXER_EXIT :
                return array($state);
        }
        return false;
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {

        if (empty($data)) return false;
        $state = $data[0];

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
