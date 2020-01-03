# dwmkerr.com

Code, articles and utilities for the [dwmkerr.com](https://dwmkerr.com) website.

<!-- vim-markdown-toc GFM -->

* [Introduction](#introduction)
* [Structure](#structure)
* [Ghost Guide](#ghost-guide)
    * [Setting up the Ghost server](#setting-up-the-ghost-server)
    * [Backup](#backup)
* [Hugo](#hugo)
* [CI/CD](#cicd)
* [Tests](#tests)
* [Theming Tasks](#theming-tasks)

<!-- vim-markdown-toc -->

## Introduction

Note that the site is currently hosted using a self-managed version of Ghost. I am planning to migrate the whole setup to a static site generated.

## Structure

The structure of this project is:

| Folder     | Usage                                      |
|------------|--------------------------------------------|
| `_wip`     | Work in progress articles and ideas.       |
| `articles` | Published blog articles.                   |
| `backups`  | Backups of the site data.                  |
| `ghost`    | Everything related to Ghost setup.         |
| `scripts`  | Helper scripts for operations like backup. |

## Ghost Guide

The following guides are used for the Ghost server.

### Setting up the Ghost server

Run the ansible playbook to setup Ghost:

```
cd ghost/ansible
absible-galaxy install -i requirements.yml
ansible-playbook -i inventory.cfg site.yml
```

### Backup

- Use the admin backup facility.
- Then copy the `content/images` and `content/themes` folder.
-rvm use --create --rvmrc 2.6@dwmkerr.com Faster than the above, run the `./scripts/backup.sh` script.

## Hugo

This website uses the [Hugo](https://gohugo.io/) static site generator. For the details of why Hugo was chosen, see [TODO](TODO).

To setup your local environment work on the site, run:

```sh
make setup
```

Then serve the site in development mode with:

```sh
make serve
```

The site can be built with:

```sh
make build

# The built site is in the public folder below.
# dwmkerr.com/public
```

## CI/CD

Any changes to the `master` branch, or a branch which matches the `build/*` pattern will trigger a deployment to GitHub Pages.

The address of the deployed site is:

https://dwmkerr.github.io/dwmkerr.com/

## Tests

- [ ] Make sure featured image is still shown for social media sharing
- [ ] GA
- [ ] Disqus

## Theming Tasks

- [ ] Update the links to be blue, and a bit more visible.
- [ ] Move the menu to the top of the page.
- [ ] Add social media share buttons.
