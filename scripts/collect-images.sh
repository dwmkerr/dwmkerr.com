#!/usr/bin/env bash

# Used to move each post which is in the post directory into it's own folder,
# inside a folder for the year it was written in.

# Go through each post.
for post_path in dwmkerr.com/content/post/*/*/*.md; do
    # Find all HTML image tags.
    image_paths=$(grep -Eoi '<img [^>]+>' $post_path |
        grep -Eo 'src="[^\"]+"' |
        sed -e 's/src=\"\(.*\)\"/\1/g')

    # If we found no images, move on.
    [ -n "$image_paths" ] || continue

    # For every image found, prepare to copy to the post directory.
    for image_path in $image_paths; do
        image_name=$(basename $image_path)

        echo "cp \""
        echo "  dwmkerr.com/static$image_path \""
        echo "  $(dirname $post_path)/$image_name"
    done

    # Note this fails for the git downloaded images.
    # Note this fixes the wp-upload folders, finally simplifynig the structure.
    # Note that we can strip out some stuff with this:
    # :%s/https:\/\/github.com\/dwmkerr\/effective-shell\/blob\/master\/1-navigating-the-command-line\///gc 
    # Note that embedded html such as tables is omitted.

    # perl -p -e 's/\<img\s+width="(\d+px)"\s+alt="([^\"]+)"\s+src="([^\"]+)"\s\/\>/{{< figure src="$3" title="$2" width="$1" >}}/g' "$post_path" 

    # Grep out any markdown images.

done
