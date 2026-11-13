---
name: resolve-issue
description: Take a dwmkerr.com GitHub issue to a reviewable PR in one pass, with before/after evidence. Use when the user says "resolve issue #N", "fix issue #N" or "zero-shot this".
---

# Resolve an issue

A lightweight first attempt: issue in, PR out. Running this skill is the go-ahead to push a branch and open a PR. Prefix `git push`, `gh pr create` and `gh api` writes with `SIGNALBOX_ALLOW_PUSH=1`. Merge or release only when the user asks.

## 1. Read and decide

`gh issue view <n> --repo dwmkerr/dwmkerr.com --comments`. If the fix needs a design decision the issue doesn't make, or is clearly large, comment on the issue with the cause and options instead of coding.

## 2. Branch and capture "before"

```bash
git fetch origin && git switch -c fix/<n>-<slug> origin/main
```

Capture the problem before changing code: a screenshot (Playwright, desktop `1280x800` and mobile `390x844` for layout issues) or command output (`curl`, built HTML). Save to `scratch/resolve-<n>/`.

## 3. Fix and check

- `cd dwmkerr.com && hugo --minify` must build.
- Posts changed: `scripts/check-social-images.sh` must pass (CI runs it too).
- Capture "after" under the same conditions as "before".

## 4. Upload images

PR bodies can't take attachments through the API. Images go on the `pr-assets` branch (no shared history with `main`), one folder per issue:

```bash
sips -Z 1400 scratch/resolve-<n>/*.png
base64 -i scratch/resolve-<n>/<file>.png | tr -d '\n' > /tmp/img.b64
SIGNALBOX_ALLOW_PUSH=1 gh api -X PUT repos/dwmkerr/dwmkerr.com/contents/<n>/<file>.png \
  -f message="chore: screenshot for #<n>" -f branch=pr-assets -F content=@/tmp/img.b64
```

URL: `https://github.com/dwmkerr/dwmkerr.com/blob/pr-assets/<n>/<file>.png?raw=true`. The repo is public, so the image is too.

## 5. Open the PR

The title must be a release-triggering conventional commit (`fix:`, `docs:`, `feat:`) when the site changes, or the release never deploys. Body:

```markdown
## Summary
- <what changed and why>

Closes #<n>

## Before and after
| | Before | After |
| --- | --- | --- |
| <surface> | <img or output> | <img or output> |

## Checks
- <build, social image check, anything not checked and why>
```

## 6. Ship (only when asked)

Squash-merge the PR, then merge the `chore(main): release x.y.z` PR that release-please opens. The release workflow deploys to `gh-pages`. Confirm the change on https://dwmkerr.com.
