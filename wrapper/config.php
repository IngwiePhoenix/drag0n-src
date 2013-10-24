<?php

include "utils.php";

// drag0n configuration.
// builds the build.ninja file

$pwd = getcwd();
$ninja = '# drag0n ninja build

# Basics
include '.$pwd.'/ninja/basic.ninja'."\n";

echo <<<CONF
drag0n configurator. Use on your own risk :)
Running with PATH: {$_ENV['PATH']}
CONF;
nl();

// Step 1: Check for ocmpilers and basic tools
$bin = array(
	'gcc'=>array(
		'getVersion'=>function($bin) {
			list($version) = split("\n", trim(shell_exec("$bin -dumpversion")) );
			return $version;
		}
	),
	'g++'=>array(
		'getVersion'=>function($bin) {
			list($version) = split("\n", trim(shell_exec("$bin -dumpversion")) );
			return $version;
		}
	),
	'make'=>array(
		'getVersion'=>function($bin) {
			$varr = split("\n", trim(shell_exec("$bin --version")) );
			return str_replace("GNU Make ","",$varr[0]);
		}
	),
);

configure($bin);
nl();

// extract paths
$gcc = $bin['gcc']['path'];
$gpp = $bin['g++']['path'];
$make = $bin['make']['path'];

$ninja .= '# Main rules

# Global include path
inc = '.$pwd.'

# Directly talk to gcc/g++
rule dgcc
  command = '.$gcc.' -I$inc -o $out $in
  description = GCC $in => $out
rule dgpp
  command = '.$gpp.' -I$inc -o $out $in
  description = G++ $in => $out

# Only invoke compile steps
rule gcc
  command = '.$gcc.' -I$inc -o $out -c $in
  description = GCC $in => $out
rule gpp
  command = '.$gpp.' -I$inc -o $out -c $in
  description = G++ $in => $out

# Configure, make, make install.
flags = 
rule unixMake
  command = '.$pwd.'/$out/configure --prefix='.$pwd.'/build/$out $flags && '.$make.' && '.$make.' install
  description = Building: $out
  
include '.$pwd.'/dylibbundler/dylibbundler.ninja

# Build libraries'."\n";
foreach(array(
	'libxml', 'libcurl', 'pth',
	'libassaun', 'libgpg-error', 'gpg', 'gpgme',
	'ucl', 'upx', 'p7zip'
) as $lib) $ninja .= "build $lib: unixMake\n";
$ninja .= '# Build the backbone
build php: unixMake
  flags = $
    --with-curl='.$pwd.'/build/libcurl $
    --with-xml='.$pwd.'/build/libxml $
    --with-pthreads='.$pwd.'/build/pth $
    --with-gpgme='.$pwd.'/build/gpgme $
    --enable-pthreads


# Building the bundle by bootstrapping it via the script
# subninja bundle/drag0n.ninja

# Perform sanity checks on the bundle
# build drag0n-test: exec ./wrapper/drag0n-test

# Make everything clean
# build cleanup: exec ./wrapper/make-distclean'."\n";

echo <<<NOTICE


One of drag0n's dependencies is Chromium. You have three methods to get it:

	1: Use a pre-bundled source tree to build chromium.
	2: Clone a fresh source tree from Google (via git and gclient) and use this for building.
	3: Update a pre-bundled source tree and use it for building
	4: Use pre-compiled binaries and skip building chromium.
	

NOTICE;
$ninja .= "# Build chromium\n";
switch(readline("Answer [1|2|3|4]> ")) {
	case 1:
		$ninja .= "build chromium: exec $pwd/wrapper/chromium.bundle.sh";
	break;
	case 2:
		$ninja .= "build chromium: exec $pwd/wrapper/chromium.gclient.sh";
	break;
	case 3:
		$ninja .= "build chromium: exec $pwd/wrapper/chromium.update-bundle.sh";
	break;
	default:
		echo "A false answer was given, using the default: Method 4\n";
	case 4:
		$ninja .= "build chromium: exec $pwd/wrapper/chromium.binaries.sh";
	break;
}
$ninja .= "\n";

echo $ninja;