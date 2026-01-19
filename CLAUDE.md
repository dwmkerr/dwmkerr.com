# CLAUDE.md

## Build Commands

**CRITICAL**: Always run the Hugo build before pushing to verify the site compiles:

```bash
cd dwmkerr.com && hugo --minify
```

This catches template errors and syntax issues that would fail CI.

## Project Structure

- `dwmkerr.com/` - Hugo site root
- `dwmkerr.com/content/post/` - Blog posts
- `dwmkerr.com/themes/ghostwriter/` - Site theme
