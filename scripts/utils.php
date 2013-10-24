<?php

function nl() { echo "\r\n"; }

function which($bin) {
	return trim( shell_exec("which $bin") );
}

function configure(array &$list) {
	foreach($list as $what => &$data) {
		echo "| Checking: $what => ";
		$data['path'] = which($what);
		if(empty($data['path'])) {
			echo "Not found!\n";
			exit(-1);
		}
		$data['version'] = $data['getVersion'](escapeshellcmd($data['path']));
		echo "{ ".$data['path'].' @ '.$data['version']." }\n";
	}
}

function dumpversion($bin) {
	$bin = escapeshellcmd($bin);
	$ver = null;
	foreach(array('-v', '--version', '-dumpversion') as $switch) {
		$out = trim(shell_exec("$bin $switch"));
		if(strlen($out) <= 10) $ver=$out; 
		else continue;
	}
	return $ver;
}