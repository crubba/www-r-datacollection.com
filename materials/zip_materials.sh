#! usr/bin/env bash

_dir=$(pwd)
today=$(date +'%Y-%m-%d')
filename="materials.zip"
find ./* -type f | grep ^\\.\\/ch | zip "$_dir/$filename" -@
