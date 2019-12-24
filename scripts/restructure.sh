#!/usr/bin/env bash

# Used to move each post which is in the post directory into it's own folder,
# inside a folder for the year it was written in.

# Go through each post.
for post_path in dwmkerr.com/content/post/*.md; do
    echo "Found $post_path"
    filename=$(basename -- "$post_path")
    filename="${filename%.*}"

    # Grep out the date line.
    dateline=$(grep -E "^date: " "$post_path")

    # We know how to get the year as the date line is consistent in all posts:
    # date: "2012-12-09T16:11:27Z"
    year=${dateline:7:4}

    # Create the folder for the post.
    new_folder="dwmkerr.com/content/post/$year/$filename"
    mkdir -p "$new_folder"

    # Move the post.
    mv "$post_path" "$new_folder/index.md"
    echo "  -> $new_folder/index.md"
done
