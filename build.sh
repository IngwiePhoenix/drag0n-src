#!/bin/bash


echo "drag0n automated building tool. Copyright Ingwie Phoenix, 2013"
echo
echo

echo "==> Internal setup"
	echo cd $(dirname "$0")
	if [ ! -d bin ]; then
		echo mkdir bin
	fi
	echo export PATH=$(pwd)/bin:$PATH

if [ ! -f ./bin/php ]; then
echo "==> Speed-building PHP"
	echo -e "\t--> Configuring"
	echo ./php/configure --disable-all --prefix=/tmp --bindir=$(pwd)/bin
	echo -e "\t--> Building"
	echo cd ./php
	echo make
	echo -e "\t--> Extracting binary"
	echo make install
	echo cd ..
fi

if [ ! -f ./bin/ninja ]; then
echo "==> Speed-building Ninja"
	echo ./ninja/bootstrap.py
	echo cp ./ninja/ninja ./bin
fi

echo "==> Configuring drag0n..."
	echo ./bin/php ./wrapper/configure.php

if [ ! -f ./build.ninja ]; then
	echo "!!! ERROR: There must have been an error while configuring. The ninja-build file is not available"
	exit -1
fi

echo "==> Building drag0n using ninja"
	echo ./bin/ninja

exit 0