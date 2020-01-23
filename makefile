# Setup tools required for local development.
.PHONY: setup
setup:
	brew install hugo
	hugo version
	git submodule update --init --recursive --remote

# Create a new post.
.PHONY: newpost
newpost:
	cd dwmkerr.com; hugo new posts/my-first-post.md

# Serve the site locally for testing.
.PHONY: serve
serve:
	cd dwmkerr.com; hugo server --baseURL "http://localhost/" --buildDrafts -v --debug

# Build the site.
.PHONY: build
build:
	cd dwmkerr.com; hugo --minify
