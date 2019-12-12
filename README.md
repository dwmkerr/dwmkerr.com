# dwmkerr.com

Code, articles and utilities for the [dwmkerr.com](https://dwmkerr.com) website.

<!-- vim-markdown-toc GFM -->

* [Introduction](#introduction)
* [Structure](#structure)
* [Ghost Guide](#ghost-guide)
    * [Setting up the Ghost server](#setting-up-the-ghost-server)
    * [Backup](#backup)

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
- Faster than the above, run the `./scripts/backup.sh` script.
