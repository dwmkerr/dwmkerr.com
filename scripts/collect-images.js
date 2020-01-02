//  Note: requires node 12.
const fs = require('fs');
const os = require('os');
const path = require('path');
const readline = require('readline');
const child_process = require('child_process')

//  Regexes we'll use repeatedly to find image tags or markdown images.
const rexImgTag = new RegExp(/<img\s+([^>]*)[/]?>/);
const regImgSrcAttribute = new RegExp(/src=\"([^"]+)"/);
const regImgAltAttribute = new RegExp(/alt=\"([^"]+)"/);
const regImgWidthAttribute = new RegExp(/width=\"([^"]+)"/);
const rexMarkdownImage = new RegExp(/\!\[([^\]]*)\]\(([^\)]+)\)/);

/**
 * moveFileSafeSync - move src to dest, ensuring all required folders in the
 * destination are created.
 *
 * @param src - the source file path
 * @param dest - the destination file path
 * @returns {undefined}
 */
function moveFileSafeSync(src, dest) {
  const directory = path.dirname(dest);
  if (!fs.existsSync(directory)) fs.mkdirSync(directory, { recursive: true } );
  fs.copyFileSync(src, dest);
  fs.unlinkSync(src);
}

/**
 * downloadFile - download a file from the web, ensures the folder for the
 * destination exists.
 *
 * @param src - the source fiile
 * @param dest - the download destination
 * @returns {undefined}
 */
function downloadFile(src, dest) {
  const directory = path.dirname(dest);
  if (!fs.existsSync(directory)) fs.mkdirSync(directory, { recursive: true } );
  const command = `wget "${src}" -P "${directory}"`;
  return child_process.execSync(command);
}

// Thanks: https://gist.github.com/kethinov/6658166
function findInDir (dir, filter, fileList = []) {
  const files = fs.readdirSync(dir);

  files.forEach((file) => {
    const filePath = path.join(dir, file);
    const fileStat = fs.lstatSync(filePath);

    if (fileStat.isDirectory()) {
      findInDir(filePath, filter, fileList);
    } else if (filter.test(filePath)) {
      fileList.push(filePath);
    }
  });

  return fileList;
}

/**
 * processPost
 *
 * @param rootPath
 * @param postPath
 * @returns {undefined}
 */
function processPost(rootPath, postPath) {
  return new Promise((resolve, reject) => {
    //  Get some details about the post which will be useful.
    const postDirectory = path.dirname(postPath);
    const postFileName = path.basename(postPath);
    console.log(`  Processing: ${postFileName}`);

    //  Create the input and output streams. Track whether we change the file.
    const updatedPostPath = `${postPath}.updated`;
    const inputStream = fs.createReadStream(postPath);
    const outputStream = fs.createWriteStream(updatedPostPath, { encoding: 'utf8'} );
    let changed = false;

    //  Read the file line-wise.
    const rl = readline.createInterface({
        input: inputStream,
        terminal: false,
        historySize: 0
    });

    //  Process each line, looking for image info.
    rl.on('line', (line) => {

      //  Check for html image tags.
      if (rexImgTag.test(line)) {
        const imageTagResults = rexImgTag.exec(line);
        const imageTag = imageTagResults[0];
        const imageTagInner = imageTagResults[1];
        console.log(`    Found image tag contents: ${imageTagInner}`);

        //  Rip out the component parts.
        const src = regImgSrcAttribute.test(imageTagInner) && regImgSrcAttribute.exec(imageTagInner)[1];
        const alt = regImgAltAttribute.test(imageTagInner) && regImgAltAttribute.exec(imageTagInner)[1];
        const width = regImgWidthAttribute.test(imageTagInner) && regImgWidthAttribute.exec(imageTagInner)[1];
        console.log(`    src: ${src}, alt: ${alt}, width: ${width}`);

        //  If the source is already in the appropriate location, don't process it.
        if (/$images\//.test(src)) {
          console.log(`    skipping, already processed`);
          outputStream.write(line + os.EOL);
          return;
        }

        //  Now that we have the details of the image tag, we can work out the
        //  desired destination in the images folder.
        const imageFileName = path.basename(src);
        const newRelativePath = path.join("images", imageFileName);
        const newAbsolutePath = path.join(postDirectory, newRelativePath);

        //  If the file is on the web, we need to download it...
        if (/http/.test(src)) {
          console.log(`    Downloading '${src}' to '${newAbsolutePath}'...`);
          downloadFile(src, newAbsolutePath);
        }
        //  ...otherwise we can just move it.
        else {
          const absoluteSrc = path.join(rootPath, src);
          moveFileSafeSync(absoluteSrc, newAbsolutePath);
          console.log(`    Copied '${absoluteSrc}' to '${newAbsolutePath}'`);
        }

        //  Now re-write the image tag.
        const newImgTag = `<img src="${newRelativePath}"${alt ? ` alt="${alt}"` : ''}${width ? ` width="${width}"` : ''} />`;
        console.log(`    Changing : ${imageTag}`);
        console.log(`    To       : ${newImgTag}`);
        line = line.replace(imageTag, newImgTag);
        changed = true;
      }
      
      //  Check for markdown image tags.
      if (rexMarkdownImage.test(line)) {
        const markdownImageCaptures = rexMarkdownImage.exec(line);
        const markdownImage = markdownImageCaptures[0];
        const markdownImageDescription = markdownImageCaptures[1];
        const markdownImagePath = markdownImageCaptures[2];
        console.log(`    Found markdown image: ${markdownImagePath}`);

        //  If the source is already in the appropriate location, don't process it.
        if (/$images\//.test(markdownImagePath)) {
          console.log(`    skipping, already processed`);
          outputStream.write(line + os.EOL);
          return;
        }

        //  Now that we have the details of the image tag, we can work out the
        //  desired destination in the images folder.
        const imageFileName = path.basename(markdownImagePath);
        const newRelativePath = path.join("images", imageFileName);
        const newAbsolutePath = path.join(postDirectory, newRelativePath);

        //  If the file is on the web, we need to download it...
        if (/http/.test(markdownImagePath)) {
          console.log(`    Downloading '${markdownImagePath}' to '${newAbsolutePath}'...`);
          downloadFile(markdownImagePath, newAbsolutePath);
        }
        //  ...otherwise we can just move it.
        else {
          const absoluteSrc = path.join(rootPath, markdownImagePath);
          moveFileSafeSync(absoluteSrc, newAbsolutePath);
          console.log(`    Copied '${absoluteSrc}' to '${newAbsolutePath}'`);
        }

        //  Now re-write the markdown.
        const newMarkdownImage = `![${markdownImageDescription}](${newRelativePath})`;
        console.log(`    Changing : ${markdownImage}`);
        console.log(`    To       : ${newMarkdownImage}`);
        line = line.replace(markdownImage, newMarkdownImage);
        changed = true;
      }

      outputStream.write(line + os.EOL);
    });


    rl.on('error', (err) => {
      console.log(`  Error reading file: ${err}`);
      return reject(err);
    });

    rl.on('close', () => {
      console.log(`  Completed, written to: ${updatedPostPath}`);
      if (changed) moveFileSafeSync(updatedPostPath, postPath);
      else fs.unlinkSync(updatedPostPath);
      return resolve();
    });
  });
}

// #     # Find all HTML image tags.
// #     image_paths=$(grep -Eoi '<img [^>]+>' $post_path |
// #         grep -Eo 'src="[^\"]+"' |
// #         sed -e 's/src=\"\(.*\)\"/\1/g')

// #     # If we found no images, move on.
// #     [ -n "$image_paths" ] || continue

// #     # For every image found, prepare to copy to the post directory.
// #     for image_path in $image_paths; do
// #         image_name="$(basename $image_path)"
// #         destination_folder="$(dirname $post_path)/images"
// #         destination="${destination_folder}/${image_name}"

// #         # Create the destination folder.
// #         mkdir -p "$destination_folder"

// #         # if the image starts with HTTP, we'll download it.
// #         if [[ $image_path =~ ^http ]]; then
// #             set -o xtrace
// #             # wget "$image_path" -O "$destination"
// #             set +o xtrace
// #         elif [[ $image_path =~ ^/wp-content ]]; then
// #             set -o xtrace
// #             cp "dwmkerr.com/static/$image_path" "${destination_folder}"
// #             set +o xtrace
// #         else
// #             set -o xtrace
// #             # cp "dwmkerr.com/static/$image_path" "${destination_folder}"
// #             set +o xtrace
// #         fi
// #     done

// #     # Note this fails for the git downloaded images.
// #     # Note this fixes the wp-upload folders, finally simplifynig the structure.
// #     # Note that we can strip out some stuff with this:
// #     # :%s/https:\/\/github.com\/dwmkerr\/effective-shell\/blob\/master\/1-navigating-the-command-line\///gc 
// #     # Note that embedded html such as tables is omitted.

// #     # perl -p -e 's/\<img\s+width="(\d+px)"\s+alt="([^\"]+)"\s+src="([^\"]+)"\s\/\>/{{< figure src="$3" title="$2" width="$1" >}}/g' "$post_path" 

// #     # Grep out any markdown images.

// # done
// }

console.log("collect-images: Tool to co-locate blog post images")
console.log("");

//  Get the directory to search. Arg 0 is node, Arg 1 iis the script path, Arg 3 onwards are commandline arguments.
const sourceDirectory = process.argv[2] || process.cwd();
console.log(`Source Directory: ${sourceDirectory}`);
const rootDirectory = process.argv[3] || sourceDirectory;
console.log(`Root Directory: ${rootDirectory}`);
console.log("");

//  Find all blog posts.
const postPaths = findInDir(sourceDirectory, /\.md$/);

//  Process each path.
postPaths.forEach(postPath => processPost(rootDirectory, postPath));

//  Let the user know we're done.
console.log(`Completed processing ${postPaths.length} file(s)`);

