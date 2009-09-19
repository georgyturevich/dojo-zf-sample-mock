<?php

class Dojend_Debug
{
    static protected $_typePrint = 'print_r';
    
    static public function pr($object, $debug = 1, $printAllBacktrace = 0, $returnDumpText = false)
    {
        $backtrace = debug_backtrace();
        $oldTypePrint = self::$_typePrint;
        self::$_typePrint = 'print_r';
        $return = self::_print($object, $debug, $backtrace, $printAllBacktrace, $returnDumpText);
        self::$_typePrint = $oldTypePrint;        
        return $return;
    }
    
    static public function vd($object, $debug = 1, $printAllBacktrace = 0, $returnDumpText = false)
    {
        $backtrace = debug_backtrace();
        $oldTypePrint = self::$_typePrint;
        self::$_typePrint = 'var_dump';
        $return = self::_print($object, $debug, $backtrace, $printAllBacktrace, $returnDumpText);
        self::$_typePrint = $oldTypePrint;        
        return $return;
    }
    
    /**
     * Функция выводит содержание переменной на экран
     *
     * @param mixed $object
     * @param bool $debug
     * @param array $backtrace
     * @param bool $printAllBacktrace
     * @param bool $returnDumpText
     * @return string
     */
    static protected function _print($object, $debug=1, $backtrace = array(), $printAllBacktrace = false, $returnDumpText = false) 
    {
        static $debugCounter = 0;
        
        $dumpText = '';
        
        ob_start();
        
        if(self::$_typePrint == 'print_r') { 
            print_r($object);
        } elseif (self::$_typePrint == 'var_dump') {
            var_dump($object);
        } else {
            throw new Exception('Unknown _typePrint "' . self::$_typePrint . '"');
        }
        
        $textObject = ob_get_clean();        
        
        if($backtrace) { 
            $file = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : '';
            $line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : '';
            $className = isset($backtrace[1]['class']) ? $backtrace[1]['class'] : '';
            $functionName = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : '';
        } else {
            $file = '';
            $line = '';
            $className = '';
            $functionName = '';
        }
        
        if($debug) {
    
            ob_start();
    
            $backtraceString = '';
            if($printAllBacktrace) { 
                foreach ($backtrace as $i=>$oTrace) {
                    foreach (array('file', 'line', 'class', 'function') as $tkey) {
                        $oTrace[$tkey] = isset($oTrace[$tkey]) ? $oTrace[$tkey] : '';
                    } 
                    $backtraceString .= "#$i: {$oTrace['file']} {$oTrace['line']} Class: {$oTrace['class']} Function: {$oTrace['function']}";
                    if($debug === 1) {
                        $backtraceString .= "<br>";
                    } elseif ($debug === 2) {
                        $backtraceString .= "\n";
                    }
                }
            }
            if($debug === 1) { 
                $rows = sizeof(explode("\n", $textObject));
                $rows = $rows > 25 ? 25 : $rows;
                $textObject = htmlspecialchars($textObject);
                $textFieldStyle = "style='width: 100%; border-width:1px; border-color : #000000; background-color : #CCCCCC; font-family: Courier; font-size: 10px'";
                
                ?>
                <div style="border: 1px solid #CCCCCC; margin-bottom: 5px; paddint-top:0px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                <tr><td>
                <? if($rows > 1) { ?>
                <textarea rows="<?=$rows?>" <?=$textFieldStyle?>><?=$textObject?></textarea>
                <? } else { ?>
                <input type="text" value="<?=$textObject?>" <?=$textFieldStyle?> <?=$textFieldStyle?>>
                <? } ?>
                </td></tr>
                <tr><td style="font-size: 10px; font-family: Verdana; width: 100%; margin-top:0px;">
                <? if(!$printAllBacktrace) { ?>
                    <?=$file?> <?=$line?>. Class:<?=$className?> , Function: <?=$functionName?>
                <? } else { ?>
                    <?=$backtraceString?>
                <? } ?>
                </td></tr>
                </table>
                </div>
            
            <?
            } elseif($debug === 2) {
                $debugCounter++;
                echo "[[[[[[ Start debug: $debugCounter $line:$file\n$textObject]]]]]]\n$backtraceString\nEnd debug: $debugCounter\n";                
            }
            
            $dumpText = ob_get_clean();
        
            if(!$returnDumpText) { 
                echo $dumpText;
            }
        }
            
        if($returnDumpText) { 
            return $dumpText;
        } else {
            return $textObject;    
        }
    }
}