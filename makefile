# Setup tools required for local development.
setup:
	brew install hugo
	hugo vesion

# Createe a new post.
newpost:
	cd dwmkerr.com; hugo new posts/my-first-post.md

# Serve the site locally for testing.
serve:
	cd dwmkerr.com; hugo server --buildDrafts

# Build the site.
build:
	cd dwmkerr.com; hugo --minify

.PHONY: setup serve
