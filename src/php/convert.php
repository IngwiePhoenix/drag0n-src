<?php

# PHP makefile to ninja

/*
	1. Distingush between --mode=compile and --mode=link and --mode=install
	2. extract flags and store them into array keys - basically, per extension.
	3. extract the output files, sort them to the flags - you see, half of the rules is done.
		"source.c" => [
			"rule"=>"extname_cc",
			"out"=>"source.o"
		]
	4. build the rules
		"extname_cc" => [
			"flags"=>"...",
		]
	5. Write the ninja file.
		rule extname_cc
		  command = libtool --mode=compile gcc <FLAGS> -c $in -o $out
		  description = CC: $out
		# snip
		build TSRM/TSRM.o: main_cc TSRM/TSRM.c
	6. Done, user now can use ninja.
*/

# Clear the source first, otherwise we're getting an incorrect output.
shell_exec("make clean");
# The commands
$cmds = explode("\n", trim(shell_exec('make --dry-run')));
# Now, lets gather all the commands into the libtool array...which we need first.
$libtools = [
	'mode=compile'=>[],
	'mode=link'=>[],
	'mode=install'=>[]
];
$rest=[];
foreach($cmds as $cmd) {
	# Split!
	$parts = explode(" ",$cmd);
	# Search
	switch($parts[0]) {
		case 'cc':
			$libtools['mode=link'][]=$cmd;
		break;
		case 'gcc':
		case 'g++':
			$libtools['mode=compile'][]=$cmd;
		break;
		default:
			$found=false;
			foreach($parts as $p) {
				if($p=="--mode=compile") $libtools['mode=compile'][]=$cmd;
				if($p=="--mode=link") $libtools['mode=link'][]=$cmd;
				if($p=="--mode=install") $libtools['mode=install'][]=$cmd;
				if(
					$p=="--mode=compile" 
					|| $p=="--mode=link" 
					|| $p=="--mode=install"
				) { $found=true; break; }
			}
		break;
	}
	# We came here, so we just gonna sadly admit an unknown command.
	if(!$found) $rest[]=$cmd;
	else continue;
}

# Now we have sorted everything where it needs to be. Next, let's figure out some things. Like...outputs. We gonna create rules off them even.
$rules = [];
$build = [];

# work out the compiles
foreach($libtools['mode=compile'] as $cmd) {
	# Search for a compiler to set a good description.
	# Also, the output and input.
	$c=null; $in=null; $out=null;
	$psArr = explode(" ",$cmd);
	foreach($psArr as $psi=>$psv) {
		$unset=false;
		if($psv=="-c") {
			$in=$psArr[$psi+1];
			echo "=> Added in=$in\n";
			$unset=true;
		} elseif($psv=="-o") {
			$out=$psArr[$psi+1];
			echo "=> Added out=$out\n";
			$unset=true;
		}
		if($unset) {
			unset($psArr[$psi]);
			unset($psArr[$psi+1]);
		}
	}
	$cmd=implode(" ",$psArr);
	$prefix=basename(dirname($in));
	$rules["${prefix}_cc"] = [
		'command' => "$cmd -w -c \$in -o \$out", #Yes we sneak in an anti-warning flag. =p
		'description' => 'CC: $out'
	];
	$build[$out]=[ 'in'=>$in, 'rule'=>"${prefix}_cc" ];
}

#Now the linking
foreach($libtools['mode=link'] as $cmd) {
	# This is a bit easier imHo. Here, we just have to search for everything that does NOT start with a dash, but has a dot.
	$command="";
	$objs=[];
	$linkOut=null;
	$parts=explode(" ",$cmd);
	foreach($parts as $i=>$p) {
		$fi = pathinfo($p);
		if(substr($p,0,1)!="-" && isset($fi['extension']) && ($fi['extension']=="lo" || $fi['extension']=="o")) {
			$objs[]=$p;
			unset($parts[$i]);
		}
		if($p=="-o") {
			$linkOut=$parts[$i+1];
			unset($parts[$i]);
			unset($parts[$i+1]);
		}
	}
	$cmd=implode(" ",$parts);
	$name=basename($linkOut);
	$rules['link_'.$name]=[
		'command'=>"$cmd \$in -o \$out >/dev/null 2>/dev/null", #Turning off pesky errors and non-neccesary messages.
		'description'=>'LINK: $out'
	];
	$build[$linkOut]=['in'=>implode(" ",$objs), 'rule'=>'link_'.$name];
	# Now, we're about to use .o files at some point. For that to work, we'll need to phony them all
	foreach($objs as $obj) {
		$fi=pathinfo($obj);
		if($fi['extension']=='o') {
			$o=str_replace(".o",".lo",$obj);
			$build[$obj]=['rule'=>'phony', 'in'=>$o];
		}
	}
}

# Now, build the ninja file.
$ninja=['# Generated using autoNinja'];
foreach($rules as $name=>$info) {
	$ninja[]="rule $name\n  command = {$info['command']}\n  description = {$info['description']}";
}
$ninja[]=null;#space
foreach($build as $out=>$info) {
	$ninja[]="build $out: {$info['rule']} {$info['in']}";
}
$ninja[]=null;#space

file_put_contents("build.ninja", implode("\n",$ninja));