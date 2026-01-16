default: help

.PHONY: help
help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

.PHONY: init
init: # Setup tools required for local development.
	brew install hugo
	hugo version
	git submodule update --init --recursive --remote

.PHONY: newpost
newpost: # Create a new post.
	cd dwmkerr.com; hugo new posts/my-first-post.md

.PHONY: dev
dev: # Serve the site locally for testing.
	cd dwmkerr.com; hugo server --baseURL "http://localhost/" --buildDrafts --logLevel debug -p 3965

.PHONY: build # Build the site.
build:
	cd dwmkerr.com; hugo --minify
