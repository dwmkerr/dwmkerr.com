# dwmkerr.com

Code, articles and utilities for the [dwmkerr.com](https://dwmkerr.com) website.

<!-- vim-markdown-toc GFM -->

* [Introduction](#introduction)
* [Structure](#structure)
* [Hugo](#hugo)
* [CI/CD](#cicd)

<!-- vim-markdown-toc -->

## Introduction

This is the code for the [dwmkerr.com](https://dwmkerr.com) website. It is a static site generated with [Hugo](https://gohugo.io/)

## Structure

The structure of this project is:

| Folder        | Usage                                           |
|---------------|-------------------------------------------------|
| `_wip`        | Work in progress articles and ideas.            |
| `dmwkerr.com` | The actual Hugo website                         |
| `scripts`     | Helper scripts.                                 |
| `makefile`    | A makefile to build the site, run locally, etc. |

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
