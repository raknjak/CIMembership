<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
 * Codeigniter HTMLPurifier Helper
 *
 * Purify input using the HTMLPurifier standalone class.
 * Easily use multiple purifier configurations.
 *
 * @author     Tyler Brownell <tyler@bluefoxstudio.ca>
 * @copyright  Public Domain
 * @license    http://bluefoxstudio.ca/release.html
 *
 * @access  public
 * @param   string or array  $dirty_html  A string (or array of strings) to be cleaned.
 * @param   string           $config      The name of the configuration (switch case) to use.
 * @return  string or array               The cleaned string (or array of strings).
 */
if (!function_exists('html_purify')) {
    function html_purify($dirty_html, $config = false)
    {
        require_once APPPATH .'vendor/htmlpurifier-4.9.2-standalone/HTMLPurifier.standalone.php';

        if (is_array($dirty_html)) {
            foreach ($dirty_html as $key => $val) {
                $clean_html[$key] = html_purify($val, $config);
            }
        } else {
            $ci = &get_instance();
            switch ($config) {
                case 'comment':
                    $config = HTMLPurifier_Config::createDefault();
                    $config->set('Core.Encoding', $ci->config->item('charset'));
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    $config->set('HTML.Allowed', 'p[style],ul,ol,li,em,sup,sub,b,strong,a[href|title],abbr[title],acronym[title],blockquote[cite],code,em,i,img[src|alt|title|style],div[style],span[style],table[border|cellspacing|cellpadding|width|summary|style],thead,tbody,tr,td[colspan|rowspan|align|valign|abbr],th[colspan|rowspan|align|valign|abbr]');
                    $config->set('AutoFormat.AutoParagraph', true);
                    $config->set('AutoFormat.Linkify', true);
                    $config->set('AutoFormat.RemoveEmpty', true);
                    break;
                case 'text_ad':
                    $config = HTMLPurifier_Config::createDefault();
                    $config->set('Core.Encoding', $ci->config->item('charset'));
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    $config->set('HTML.Allowed', 'p,span[style],b,strong,em,u]');
                    $config->set('AutoFormat.AutoParagraph', true);
                    $config->set('AutoFormat.Linkify', true);
                    $config->set('AutoFormat.RemoveEmpty', true);
                    break;
                case false:
                    $config = HTMLPurifier_Config::createDefault();
                    $config->set('Core.Encoding', $ci->config->item('charset'));
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    break;
                default:
                    show_error('The HTMLPurifier configuration labeled "'.htmlspecialchars($config, ENT_QUOTES, $ci->config->item('charset')).'" could not be found.');
            }
            $purifier = new HTMLPurifier($config);
            $clean_html = $purifier->purify($dirty_html);
        }
        return $clean_html;
    }
}