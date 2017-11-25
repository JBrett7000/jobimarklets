#!/usr/bin/env bash

#
# Function test logic copied from https://gist.github.com/JamieMason/4761049
#
export BASE_DIR=`pwd`
export BACKEND="$BASE_DIR/backend-service"
export FRONTEND="$BASE_DIR/frontend"

if [ "$1"  == no-compile ];
then
    cd $BACKEND
    composer local-server
    exit
fi


# return 1 if global command line program installed, else 0
# example
# echo "node: $(program_is_installed node)"
function program_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}

# return 1 if local npm package is installed at ./node_modules, else 0
# example
# echo "gruntacular : $(npm_package_is_installed gruntacular)"
function npm_package_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  ls node_modules | grep $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}


# display a message in red with a cross by it
# example
# echo echo_fail "No"
function echo_fail {
  # echo first argument in red
  printf "✘${1}"
  exit
}

# display a message in green with a tick by it
# example
# echo echo_fail "Yes"
function echo_pass {
  # echo first argument in green
  printf "✔${1}"
}

# echo pass or fail
# example
# echo echo_if 1 "Passed"
# echo echo_if 0 "Failed"
function echo_if {
  if [ $1 -eq 1 ]; then
    echo_pass $2
  else
    echo_fail $2
    exit
  fi
}

function echo_no_error {
    if [ $1 -eq 0 ]; then
        # echo first argument in green
        printf "✔"
    else
        printf "✘"
        exit
    fi
}

# Test for available packages
echo "node      $(echo_if $(program_is_installed node))"
echo "npm      $(echo_if $(program_is_installed npm))"
echo "php       $(echo_if $(program_is_installed php))"
echo "composer  $(echo_if $(program_is_installed composer))"

# Installing Packages
echo Installing Node Packages
cd $FRONTEND
npm install 2>/dev/null
echo "Succeeded $(echo_no_error $?)"

# Installing PHP Packages
echo Installing PHP Packages
cd $BACKEND
composer update 2>/dev/null
echo "Succeeded $(echo_no_error $?)"

# Compiling Project
echo Compiling Scripts
cd $FRONTEND
npm build


# Database Migrations
echo Setting up database migrations
cd $BACKEND
composer db-setup > /dev/null 2>&1
SUCCEED=$?

if [ ${SUCCEED} -eq 1 ];
then
    echo Migration failed
    exit
else
    echo "Succeeded $(echo_no_error ${SUCCEED})"
fi

# Starting Local PHP Server
cd $BACKEND
composer local-server




