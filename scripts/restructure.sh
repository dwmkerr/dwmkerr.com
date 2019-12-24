#!/usr/bin/env bash

for post_path in dwmkerr.com/content/post/*.md; do
    echo "Found $post_path"
    filename=$(basename -- "$post_path")
    extension="${filename##*.}"
    filename="${filename%.*}"
    echo "   $filename     - $extension"
done
