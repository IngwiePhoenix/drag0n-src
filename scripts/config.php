<?php

include "utils.php";

// drag0n configuration.
// builds the build.ninja file

$pwd = realpath(dirname(__FILE__).'/..');
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
	--enable-embed=static $
	--enable-static=yes $
	--enable-maintainer-zts $
	--with-config-file-path="" $
	--with-config-file-scan-dir="" $
	--with-curl='.$pwd.'/build/libcurl $
    --with-xml='.$pwd.'/build/libxml $
    --with-pthreads='.$pwd.'/build/pth $
	--with-ssh2='.$pwd.'/build/ssh2 $
	--with-gpg='.$pwd.'/build/libgpg $
	--with-gnupg='.$pwd.'/build/gpgme $
	--with-ncurses $
	--with-tidy $
	--with-libedit $
	--with-curl='.$pwd.'/build/libcurl $
	--with-mcrypt $
	--enable-pthreads $
	--enable-mbstring $
	--enable-sockets $
	--enable-ftp $
	--enable-soap $
	--enable-zip $
	--enable-soap $
	--enable-libxml $
	--disable-opcache


# Building the bundle by bootstrapping it via the script
# subninja bundle/drag0n.ninja'."\n";

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
		$ninja .= "build chromium: exec $pwd/wrapper/chromium.git.sh";
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

echo <<<NOTICE


Another programm that drag0n needs to build itself is clang. We preffer clang over GCC/G++, and that is why we're going to bundle it later.
But just like with Chromium, you may decide how you recieve or work with clang:

	1: Download the source and build it fresh and clean
	2: Use a precompiled binary


NOTICE;
switch(readline("Answer [1|2]> ")) {
	case 1:
		$ninja .= "build llvm: exec $pwd/wrapper/llvm.build.sh\n";
		$ninja .= "build clang: exec $pwd/wrapper/clang.build.sh\n";
	break;
	default:
		echo "Invaild answer given, choosing the default: Method 2.";
	case 2:
		$ninja .= "build clang: exec $pwd/wrapper/clang.download.sh\n";
	break;
}


echo $ninja;