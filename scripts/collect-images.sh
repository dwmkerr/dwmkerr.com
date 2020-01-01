#!/usr/bin/env node



function collect_post_images() {
    # Get the posts. This'll likely be a list from a glob.
    posts=$1

    # Go through each post.
    for post_path in $posts; do
        echo "Checking '${post_path}'..."

        # Get some paths and folders we'll need.
        post_folder=$(dirname $post_path)

        # Grab all image tags.
        image_tags=$(grep -Eo '<img [^>]+>' $post_path)

        # Go through each one and decode it.
        echo $image_tags | while read -r image_tag; do
            if [ -z "${image_tags}" ]; then
                continue
            fi
            image_tag_src=$(echo $image_tag | sed -E 's/.*src="([^"]*).*/\1/')
            image_tag_alt=$(echo $image_tag | sed -E 's/.*alt="([^"]*).*/\1/')
            image_tag_width=$(echo $image_tag | sed -E 's/.*width="([^"]*).*/\1/')
            echo "  Found image tag: ${image_tag}"
            echo "    src: ${image_tag_src}"
            echo "    alt: ${image_tag_alt}"
            echo "    width: ${image_tag_width}"
        done
    done
}

collect_post_images "dwmkerr.com/content/post/2011/*/*.md"

# # Go through each post.
# for post_path in dwmkerr.com/content/post/*/*/*.md; do
#     # Find all HTML image tags.
#     image_paths=$(grep -Eoi '<img [^>]+>' $post_path |
#         grep -Eo 'src="[^\"]+"' |
#         sed -e 's/src=\"\(.*\)\"/\1/g')

#     # If we found no images, move on.
#     [ -n "$image_paths" ] || continue

#     # For every image found, prepare to copy to the post directory.
#     for image_path in $image_paths; do
#         image_name="$(basename $image_path)"
#         destination_folder="$(dirname $post_path)/images"
#         destination="${destination_folder}/${image_name}"

#         # Create the destination folder.
#         mkdir -p "$destination_folder"

#         # if the image starts with HTTP, we'll download it.
#         if [[ $image_path =~ ^http ]]; then
#             set -o xtrace
#             # wget "$image_path" -O "$destination"
#             set +o xtrace
#         elif [[ $image_path =~ ^/wp-content ]]; then
#             set -o xtrace
#             cp "dwmkerr.com/static/$image_path" "${destination_folder}"
#             set +o xtrace
#         else
#             set -o xtrace
#             # cp "dwmkerr.com/static/$image_path" "${destination_folder}"
#             set +o xtrace
#         fi
#     done

#     # Note this fails for the git downloaded images.
#     # Note this fixes the wp-upload folders, finally simplifynig the structure.
#     # Note that we can strip out some stuff with this:
#     # :%s/https:\/\/github.com\/dwmkerr\/effective-shell\/blob\/master\/1-navigating-the-command-line\///gc 
#     # Note that embedded html such as tables is omitted.

#     # perl -p -e 's/\<img\s+width="(\d+px)"\s+alt="([^\"]+)"\s+src="([^\"]+)"\s\/\>/{{< figure src="$3" title="$2" width="$1" >}}/g' "$post_path" 

#     # Grep out any markdown images.

# done
