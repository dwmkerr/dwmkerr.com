#!/usr/bin/env bash
# Fails if a post added or changed since <base> has no social image, or one
# that would 404 once deployed. Link previews (Slack, LinkedIn) silently drop
# the image in that case, so catch it before merge.
#
# Usage: scripts/check-social-images.sh [base]   (default base: origin/main)
set -euo pipefail

base="${1:-origin/main}"
cd "$(dirname "$0")/../dwmkerr.com"

changed=$(git diff --name-only --diff-filter=AM "${base}...HEAD" -- content/post \
  | grep -E '\.md$' | sed 's#^dwmkerr.com/##' || true)
if [ -z "${changed}" ]; then
  echo "No posts changed since ${base}."
  exit 0
fi

hugo --minify --quiet
# Titles can contain commas, so take the permalink counting from the end of the row.
listing=$(hugo list all 2>/dev/null)

fail=0
while IFS= read -r file; do
  permalink=$(awk -F, -v p="${file}" '$1 == p { print $(NF-2) }' <<< "${listing}")
  if [ -z "${permalink}" ]; then
    echo "SKIP ${file}: not published (draft or future date)"
    continue
  fi
  page="public/${permalink#*://*/}index.html"
  image=$(grep -oE 'property="?og:image"? content="?[^" >]+' "${page}" | sed -E 's/.*content="?//' || true)
  if [ -z "${image}" ]; then
    echo "FAIL ${file}: no og:image, add a 'thumbnail' to the front matter"
    fail=1
  elif [ ! -f "public/${image#*://*/}" ]; then
    echo "FAIL ${file}: og:image ${image} is not in the built site"
    fail=1
  else
    echo "OK   ${file}: ${image}"
  fi
done <<< "${changed}"

exit "${fail}"
