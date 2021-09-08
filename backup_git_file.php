<?php

/**
 * RD開發用Git Commit備份工具 - 將某一個git commit異動內容覆製到特定資料夾
 * 20210908 @Marco
 * 使用方式 php -q backup_git_file.php commit_id 目的資料夾/ (需加斜線)
 * EX: php -q backup_git_file.php 55a55ca65caaaddd7b07e642bb45d794f0412a96 test2/
 * EX: php -q backup_git_file.php 55a55ca65caaaddd7b07e642bb45d794f0412a96 show 顯示該commit異動檔案
 */

$commit_id = $argv[1];
$dest_fd = "";
if (isset($argv[2])) {
    $dest_fd = $argv[2];
}

if (empty($argv[1])) {
    echo "no commit";
    exit;
}

$a = shell_exec('git show --pretty="" --name-only ' . $commit_id);
$b = array_filter(explode("\n", $a));

if ($argv[2] == "show" || $argv[2] == "") {
    var_dump($b);
    exit;
}

if (count($b) > 0) {
    foreach ($b as $file) {
        $_file = array_filter(explode("/", $file));
        $s_fd = $dest_fd;
        $f_name = "";
        foreach ($_file as $val) {
            if (count($_file) == 1) {
                $f_name = $val;
            } elseif (strpos($val, ".") == false) {
                $s_fd .= $val . "/";
            } else {
                $f_name = $val;
            }
        }
        shell_exec('mkdir -p ' . $s_fd);
        if (is_dir($s_fd)) {
            shell_exec('cp -a ' . $file . ' ' . $s_fd . '/' . $f_name);
        }
    }
    shell_exec('chown www-data: -R ' . $dest_fd);
}
var_dump($b);
