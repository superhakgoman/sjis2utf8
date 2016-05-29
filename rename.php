<?php
function convertShiftJISToUTF8($dirName){
    if(is_null($dirName)){
        $dirName = __DIR__;
    }
    $curDir = opendir($dirName);
    while (($childName = readdir($curDir)) != false) {
        if (strpos($childName, '.') === 0  // 숨김 파일
        || mb_detect_encoding($childName) == "UTF-8" // 이미 UTF-8 로 변환된 파일명
        || $childName == basename(__FILE__)) {  // 지금 실행중인 스크립트 파일
            continue;
        }
        $fullPathName = $dirName . '/' . $childName;
        echo $fullPathName . PHP_EOL;
        $newChildName = mb_convert_encoding(stripcslashes($childName), "UTF-8", "Shift-JIS");
        $newFullPathName = $dirName . '/' . $newChildName;
        echo $newFullPathName . PHP_EOL;
        if(!rename($fullPathName, $newFullPathName)){
            throw new RuntimeException("Error Processing rename : " . $newFullPathName);
        }
        if(is_dir($newFullPathName)){
            convertShiftJISToUTF8($newFullPathName);
        }
    }
    closedir($curDir);
}
convertShiftJISToUTF8($argv[1]);
?>
