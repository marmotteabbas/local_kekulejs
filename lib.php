<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


defined('MOODLE_INTERNAL') || die();

/**
 * Config class kekule.js plugins
 */
class kekulejs_configs
{
    //const DEF_MOL_COMPARER_URL = 'http://127.0.0.1:3000/mols/compare';

    const DEF_KEKULE_DIR = '/local/kekulejs/';
    //const DEF_KEKULE_JS_DIR = '/local/kekule.js/scripts/';

    /**
     * Returns root dir of Kekukejs plugins.
     * @return string
     */
    static public function getKekuleDir()
    {

        $result = get_config('mod_kekule', 'kekule_dir');
        if (empty($result))
            $result = self::DEF_KEKULE_DIR;
        return $result;
    }
    /**
     * Returns dir of JavaScript files (including Kekule.js and its dependencies).
     * @return string
     */
    static public function getScriptDir()
    {
        return self::getKekuleDir() . 'jslib/';
    }
    /**
     * Returns dir of Kekule.js JavaScript files.
     * @return string
     */
    static public function getKekuleScriptDir()
    {
        return self::getScriptDir() . 'kekule.js.01012020/';
      //  return self::getScriptDir() . 'kekule/';
    }
    static public function getAdapterDir()
    {
        return self::getKekuleDir() . 'adapter/';
    }
}

class kekulejs_utils
{
    /**
     * Add essential Kekule.js Javascript files to $PAGE.
     * @param $page
     * @param null $options Array that stores options to load Kekule.js
     */
    static public function includeKekuleScriptFiles($options = null, $page = null)
    {
        global $PAGE, $CFG, $COURSE, $USER;

        $p = $page;
        if (!isset($p))
            $p = $PAGE;
        $scriptDir = kekulejs_configs::getScriptDir();
        // $rootDir = kekulejs_configs::getKekuleDir();
		$kekuleScriptDir = kekulejs_configs::getKekuleScriptDir();
        $adapterDir = kekulejs_configs::getAdapterDir();

        // params
        $params = '';
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $params .= $key . '=' . $value;
            }
        } else  // use default
            $params = 'modules=io,chemWidget,algorithm&locals=zh';
        
        $context = context_course::instance($COURSE->id);
        $access_admin = user_has_role_assignment($USER->id, 1, $context->id);
        $access_student = user_has_role_assignment($USER->id, 5, $context->id);
        $access_teacher = user_has_role_assignment($USER->id, 3, $context->id);
        $access_creator = user_has_role_assignment($USER->id, 2, $context->id);
        $access_reading_teacher = user_has_role_assignment($USER->id, 4, $context->id);
        
        //$PAGE->requires->js_call_amd('local_kekulejs/customConf', 'init');
       
        if ( (get_config('mod_kekule', 'circleArroundAdmin') == 1 
                &&
             ($access_admin || $access_teacher || $access_reading_teacher || $access_creator)
           )
           ||
           (get_config('mod_kekule', 'circleArroundStudent') == 1
                &&
           !($access_admin || $access_teacher || $access_reading_teacher || $access_creator))){
            $setChargeMarkType = 3;
        } else {
            $setChargeMarkType =  1;
        }
        
        if ((get_config('mod_kekule', 'specifiedColorAdmin') == 1
                &&
             ($access_admin || $access_teacher || $access_reading_teacher || $access_creator))
            ||
            (get_config('mod_kekule', 'specifiedColorStudent') == 1
                &&
            !($access_admin || $access_teacher || $access_reading_teacher || $access_creator))    
           ){
            $setAtomSpecifiedColor = 1;
        } else {
            $setAtomSpecifiedColor = 0;
        }
        
        
        if (( get_config('mod_kekule', 'confButtonHiddenAdmin') == 1
                &&
             ($access_admin || $access_teacher || $access_reading_teacher || $access_creator)
            )
            ||
            (get_config('mod_kekule', 'confButtonHiddenStudent') == 1
                &&
            !($access_admin || $access_teacher || $access_reading_teacher || $access_creator))    
           ){
            echo "<style>.K-Action-Open-Configurator {display: none !important;}</style>";
        }
        
        //Very dirty solution but working one, TODO : Make an clean amd call solution to fix asynchronous problems
        echo "<script>"
        . "var settings_kekule_atomspecifiedcolor= ".$setAtomSpecifiedColor.";"
        . "var settings_kekule_chargemarktype= ".$setChargeMarkType.";"
        . "</script>";
       
        $PAGE->requires->js(new moodle_url($CFG->wwwroot .$scriptDir.'raphael-min.js'));
        $PAGE->requires->js(new moodle_url($CFG->wwwroot .$scriptDir.'Three.js'));
        $PAGE->requires->js(new moodle_url($CFG->wwwroot .$kekuleScriptDir . 'kekule.js?'. $params));
        $PAGE->requires->js(new moodle_url($CFG->wwwroot.$adapterDir . 'kekuleInitials.js')); 
    }
    static public function includeKekuleJsFiles($options = null, $page = null)
    {
        return kekulejs_utils::includeKekuleScriptFiles($options, $page);
    }

    /**
     * Add essential Kekule.js CSS files to $PAGE.
     * @param $page
     */
    static public function includeKekuleCssFiles($page = null)
    {
        global $PAGE;
        $p = $page;
        if (!isset($p))
            $p = $PAGE;

        $scriptDir = kekulejs_configs::getScriptDir();
		$kekuleScriptDir = kekulejs_configs::getKekuleScriptDir();
        try {
            $p->requires->css($kekuleScriptDir . 'themes/default/kekule.css');
        }
        catch(Exception $e)
        {
            // do nothing, just avoid exception
        }
    }

    static public function includeAdapterJsFiles($page = null)
    {
        global $PAGE, $CFG;

        $p = $page;
        if (!isset($p))
            $p = $PAGE;
        $dir = kekulejs_configs::getAdapterDir();
        $p->requires->js(new moodle_url($CFG->wwwroot.$dir . 'kekuleMoodle.js'));
    }

    /**
     * Add essential Kekule.js CSS files to $PAGE.
     * @param $page
     */
    static public function includeAdapterCssFiles($page = null)
    {
        global $PAGE;

        $p = $page;
        if (!isset($p))
            $p = $PAGE;
        $dir = kekulejs_configs::getAdapterDir();
        try {
            $p->requires->css($dir . 'kekuleMoodle.css');
        }
        catch(Exception $e)
        {
            // do nothing, just avoid exception
        }
    }
}