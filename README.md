# dwmkerr.com

Code, articles and utilities for the [dwmkerr.com](https://dwmkerr.com) website.

<!-- vim-markdown-toc GFM -->

* [Introduction](#introduction)
* [Quickstart](#quickstart)
* [Structure](#structure)
* [Hugo](#hugo)
* [Theming](#theming)
    * [Using a Forked Theme](#using-a-forked-theme)
* [Developer Guide](#developer-guide)
* [CI/CD](#cicd)

<!-- vim-markdown-toc -->

## Introduction

This is the code for the [dwmkerr.com](https://dwmkerr.com) website. It is a static site build from a repository hosted at [github.com/dwmkerr/dwmkerr.com](https://github.com/dwmkerr/dwmkerr.com) generated with [Hugo](https://gohugo.io/).

The RSS feed is published to [dwmkerr.com/index.xml](https://dwmkerr.com/index.xml).

## Quickstart

To get started, clone the repo, run `make serve` to install dependencies and `make serve` to serve locally:

```sh
git clone git@github.com:dwmkerr/dwmkerr.com
make setup
make serve
```

## Structure

The structure of this project is:

| Folder        | Usage                                           |
|---------------|-------------------------------------------------|
| `_wip`        | Work in progress articles and ideas.            |
| `dmwkerr.com` | The actual Hugo website                         |
| `scripts`     | Helper scripts.                                 |
| `makefile`    | A makefile to build the site, run locally, etc. |

## Hugo

This website uses the [Hugo](https://gohugo.io/) static site generator. For the details of why Hugo was chosen, see my article [Migrating from Ghost to Hugo - Why Bother?](https://dwmkerr.com/migrating-from-ghost-to-hugo/).

## Theming

When a new release of the theme needs to be used, update the submodules with:

```sh
git submodule update --init --recursive --remote
```

### Using a Forked Theme

The site uses the [hugo_theme_pickles](https://github.com/mismith0227/hugo_theme_pickles.git) theme. If customisations are needed, it can be forked and then the theme switched:

To update this theme, use the following command.

```
git submodule add -b release git@github.com:mismith0227/hugo_theme_pickles.git dwmkerr.com/themes/hugo_theme_pickles
```

And update the `config.toml` to use this theme. Then pull request back into upstream, and when the changes are in the mainline move back to the mainline theme

## Developer Guide

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

Any changes to the `main` branch, or a branch which matches the `build/*` pattern will trigger a deployment to GitHub Pages.

The address of the deployed site is:

https://dwmkerr.github.io/dwmkerr.com/

A public domain name routes to this address.
