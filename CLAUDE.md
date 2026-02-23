# CLAUDE.md

## Build Commands

**CRITICAL**: Always run the Hugo build before pushing to verify the site compiles:

```bash
cd dwmkerr.com && hugo --minify
```

This catches template errors and syntax issues that would fail CI.

## Git Hooks

Shared hooks live in `.githooks/`. Enable them with:

```bash
git config core.hooksPath .githooks
```

Currently configured:
- `pre-push` - runs `hugo --minify` to catch build errors before pushing

## Project Structure

- `dwmkerr.com/` - Hugo site root
- `dwmkerr.com/content/post/` - Blog posts
- `dwmkerr.com/themes/hugo_theme_pickles/` - Site theme (submodule)
