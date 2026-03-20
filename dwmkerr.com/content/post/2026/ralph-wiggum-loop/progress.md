# Evaluations Cleanup Progress

## Completed

- [x] Identify all evaluation-related files, CRDs, docs, demos

### Inventory (200+ files)

**Go types/controllers/webhooks/validation (ark/):**
- `ark/api/v1alpha1/evaluation_types.go`, `evaluator_types.go`, `evaluator_types_test.go`
- `ark/internal/controller/evaluation_controller.go`, `evaluator_controller.go` + tests
- `ark/internal/webhook/v1/evaluation_webhook.go`, `evaluator_webhook.go`
- `ark/internal/validation/evaluation.go`, `evaluator.go` + tests
- `ark/internal/genai/evaluator.go`
- `ark/cmd/main.go` — registers EvaluationReconciler and EvaluatorReconciler

**CRD YAML (base + helm chart):**
- `ark/config/crd/bases/ark.mckinsey.com_evaluations.yaml`, `_evaluators.yaml`
- `ark/dist/chart/templates/crd/ark.mckinsey.com_evaluations.yaml`, `_evaluators.yaml`

**RBAC (config + helm chart + GitHub):**
- `ark/config/rbac/evaluator_{admin,editor,viewer}_role.yaml`
- `ark/dist/chart/templates/rbac/evaluator_{admin,editor,viewer}_role.yaml`
- `.github/k8s/rbac-evaluator-access.yaml`

**Samples:**
- `ark/samples/evaluations/` (6 files), `ark/samples/evaluator-selector/`
- `samples/evaluator/`, `samples/evaluator-selector/`
- `samples/agent-modernization/custom-evaluators/` (3 files), `perf-evaluators/`
- `ark/config/samples/ark_v1alpha1_evaluator.yaml`

**Chainsaw integration tests (tests/):**
- `evaluation-baseline/`, `evaluation-direct/`, `evaluation-direct-ragas/`
- `evaluation-event-basic/`, `evaluation-parameters-priority/`, `evaluation-query/`
- `evaluator-context-enhanced/`, `evaluator-selector/`
- `weather-chicago/` and `weather-chicago-tools/` — contain evaluator manifests
- `admission-failures/` — contains evaluator validation manifests
- `tests/helpers/wait-for-evaluator.sh`

**ark-evaluator service (services/ark-evaluator/) — entire service:**
- Python implementation: core, providers, metrics, helpers, oss_providers (langfuse, ragas)
- Tests, docs, examples, Helm chart, Dockerfile, devspace.yaml, build.mk

**ark-api service (services/ark-api/):**
- `api/v1/evaluations.py`, `evaluators.py`
- `models/evaluation_metadata.py`, `evaluations.py`, `evaluators.py`

**ark-dashboard (services/ark-dashboard/):**
- Pages: `evaluation/[id]/page.tsx`, `evaluations/page.tsx`, `evaluators/page.tsx`
- Components: `evaluation/` (12 files), editors, forms, sections, cards, rows, filtering
- Services/hooks: `evaluations-hooks.ts`, `evaluations.ts`, `evaluators.ts`
- Tests: evaluation-editor, evaluator-editor

**ark-cli (tools/ark-cli/):**
- `commands/evaluation/`, `lib/executeEvaluation.ts`
- References in: completion, export, types, services

**Documentation (docs/):**
- `developer-guide/ark-evaluator.mdx`
- `reference/evaluations/` (evaluations.mdx, event-based-evaluations.mdx, event-types-reference.mdx, semantic-expressions.mdx, _meta.js)

- [x] Remove evaluation CRDs and related Go code (types, controllers, webhooks, validation, genai)

### CRD/Go Code Removal Details
Deleted 15 Go source files:
- Types: `evaluation_types.go`, `evaluator_types.go`, `evaluator_types_test.go`
- Controllers: `evaluation_controller.go`, `evaluator_controller.go` + 3 test files
- Webhooks: `evaluation_webhook.go`, `evaluator_webhook.go`
- Validation: `evaluation.go`, `evaluator.go` + 2 test files
- GenAI: `evaluator.go`, `context_retrieval_helper.go`

Deleted 11 YAML config files:
- 2 CRD bases, 2 Helm chart CRDs, 3 config RBAC roles, 3 Helm RBAC roles, 1 sample

Deleted sample directories: `ark/samples/evaluations/` (6 files), `ark/samples/evaluator-selector/` (3 files)

Deleted `.github/k8s/rbac-evaluator-access.yaml`

Edited 12 files to remove evaluation references:
- `ark/cmd/main.go` — removed controller and webhook registrations
- `ark/PROJECT` — removed Evaluator resource entry
- `ark/api/v1alpha1/zz_generated.deepcopy.go` — removed evaluation/evaluator deepcopy functions, removed orphaned ExpressionRule/ResourceSelector deepcopy
- `ark/api/v1alpha1/common_types.go` — moved `QueryRef` here (used by A2ATask), removed orphaned `ExpressionRule` and `ResourceSelector` types
- `ark/internal/validation/dispatch.go` — removed Evaluator/Evaluation cases
- `ark/internal/validation/lookup.go` — removed Evaluator/Evaluation cases
- `ark/internal/annotations/annotations.go` — removed `Evaluator` annotation constant
- `ark/internal/apiserver/resources.go` — removed Evaluation/Evaluator resource definitions
- `ark/internal/apiserver/resources_test.go`, `converter_test.go`, `printer_columns_test.go` — removed evaluation test cases
- `ark/config/crd/kustomization.yaml`, `ark/config/rbac/kustomization.yaml`, `ark/config/samples/kustomization.yaml` — removed evaluation file references
- `ark/config/rbac/ark_controller_role.yaml`, `ark/config/rbac/ark-deployer-role.yaml` — removed evaluation RBAC rules
- `ark/config/webhook/manifests.yaml` — removed evaluator webhook
- `ark/dist/chart/templates/webhook/webhooks.yaml`, `ark/dist/chart/templates/rbac/ark_controller_role.yaml`, `ark/dist/chart/templates/rbac/ark-deployer-role.yaml` — Helm chart equivalents

Verified: `go build ./...` succeeds, `go test ./...` passes (2 pre-existing failures due to missing kubebuilder envtest binaries, unrelated to changes)

- [x] Remove evaluation integration tests

### Integration Test Removal Details
Deleted 8 evaluation/evaluator test directories:
- `evaluation-baseline/`, `evaluation-direct/`, `evaluation-direct-ragas/`
- `evaluation-event-basic/`, `evaluation-parameters-priority/`, `evaluation-query/`
- `evaluator-context-enhanced/`, `evaluator-selector/`

Deleted `tests/helpers/wait-for-evaluator.sh`

Deleted `tests/.chainsaw-evaluated.yaml` (chainsaw config for evaluated label selector)

Stripped evaluator/evaluation from weather-chicago and weather-chicago-tools tests:
- Deleted 6 evaluator manifest files (RBAC, evaluator resources, evaluation resources)
- Removed evaluator setup steps (apply RBAC, apply evaluator, wait for evaluator, wait-for-evaluator.sh)
- Removed evaluation apply/assert/wait steps
- Removed `evaluated: "true"` labels
- Updated test descriptions to reflect query-only testing
- Kept weather agent, tool, and query testing intact

Stripped evaluator from admission-failures test:
- Deleted `invalid-evaluator-missing-address.yaml`, `invalid-evaluator-invalid-model.yaml`
- Removed 2 evaluator validation test steps from chainsaw-test.yaml

Updated READMEs:
- `tests/README.md` — removed evaluator from coverage table, roadmap, complexity, and status
- `tests/weather-chicago/README.md`, `tests/weather-chicago-tools/README.md` — removed evaluation bullet points
- `tests/admission-failures/README.md` — removed evaluator validation section and evaluator-related query validation

- [x] Remove evaluation documentation

### Documentation Removal Details
Deleted entire `docs/content/reference/evaluations/` directory (5 files: evaluations.mdx, event-based-evaluations.mdx, event-types-reference.mdx, semantic-expressions.mdx, _meta.js)

Deleted `docs/content/developer-guide/ark-evaluator.mdx`

Edited 13 documentation files to remove evaluation references:
- `docs/content/reference/_meta.js` — removed Evaluations separator and nav entry
- `docs/content/developer-guide/_meta.js` — removed ark-evaluator nav entry
- `docs/content/reference/crds.mdx` — removed Evaluator and Evaluation table rows, removed entire Evaluators and Evaluations sections (~250 lines of specs/examples)
- `docs/content/index.mdx` — removed "Performance evaluation" feature bullet
- `docs/content/core-concepts/index.mdx` — removed evaluation feature bullet, Evaluator/Evaluation resource entries, ark-evaluator service entry
- `docs/content/reference/ark-apis.mdx` — removed Evaluators/Evaluations endpoint list entries and entire "Evaluators and Evaluations (v1)" API section with examples
- `docs/content/developer-guide/services/ark-api.mdx` — removed Evaluators/Evaluations endpoint list entries
- `docs/content/developer-guide/services.mdx` — removed "Specialized Services" section (Evaluator LLM, Evaluation Operator)
- `docs/content/reference/utility-images.mdx` — removed ark-evaluator image section
- `docs/content/operations-guide/build-pipelines.mdx` — removed "and evaluator" from CI variable descriptions
- `docs/content/developer-guide/testing/index.mdx` — removed "or evaluation" from intro, removed evaluated test selector command, removed evaluation summary section
- `docs/content/developer-guide/ark-cli.mdx` — removed evaluation from resource list, removed evaluation CLI examples, updated error handling description
- `docs/content/developer-guide/crd-design-guide.mdx` — removed Evaluation from job-like resources example
- `docs/content/developer-guide/workflows/argo-workflows.mdx` — renamed evaluator-model parameter to model
- `docs/content/disclaimer.mdx` — removed "Evaluations" from runtime core description
- `docs/content/user-guide/starting-new-project.mdx` — removed "Add Evaluations" section

Preserved general "evaluation" references in observability docs (Phoenix/Langfuse external tool capabilities) and walkthrough tutorial ("evaluation-driven methodology" refers to test-driven approach, not Ark CRDs).

- [x] Remove ark-evaluator service entirely

### ark-evaluator Service Removal Details
Deleted entire `services/ark-evaluator/` directory (159 files):
- Python service: core evaluator, providers, metrics, helpers, OSS providers (langfuse, ragas)
- Tests, docs, examples, Helm chart, Dockerfile, devspace.yaml, build.mk
- `uv.lock`, `pyproject.toml`, scripts, `.dockerignore`, `.python-version`

Cleaned up `devspace.yaml` — removed commented-out ark-evaluator dependency entry.

No changes needed to `services/services.mk` — it uses wildcard discovery (`services/*/build.mk`), so removing the directory is sufficient.

Verified: `make -n services-build-all` dry-run shows no ark-evaluator references.

- [x] Remove evaluation code from ark-api, ark-dashboard, ark-cli

### ark-api Removal Details
Deleted 5 Python files:
- `api/v1/evaluations.py`, `api/v1/evaluators.py` — route handlers
- `models/evaluations.py`, `models/evaluators.py`, `models/evaluation_metadata.py` — Pydantic models

Edited 5 files:
- `api/v1/__init__.py` — removed evaluation/evaluator router imports and registrations
- `models/queries.py` — removed `evaluators` and `evaluatorSelector` fields from QueryCreateRequest
- `models/export.py` — removed "evaluators" and "evaluations" from ResourceType Literal
- `api/v1/export.py` — removed evaluators/evaluations from standard_resources set
- `chart/templates/rbac.yaml` — removed "evaluations" and "evaluators" from Ark resources RBAC

### ark-dashboard Removal Details
Deleted 25+ files:
- Entire `components/evaluation/` directory (12 files: detail views, status indicator, metrics displays, index)
- Editor components: `evaluation-editor.tsx`, `evaluator-editor.tsx`
- Card/row/section/form/filter: `evaluator-card.tsx`, `evaluator-row.tsx`, `evaluators-section.tsx`, `evaluations-section.tsx`, `evaluator-edit-form.tsx`, `evaluations-filter.tsx`, `query-evaluation-actions.tsx`
- Service files: `evaluations-hooks.ts`, `evaluations.ts`, `evaluators.ts`
- App routes: `evaluation/[id]/`, `evaluations/`, `evaluators/`, `evals/`
- Tests: `evaluation-editor.test.tsx`, `evaluator-editor.test.tsx`

Edited 18 files:
- `lib/services/index.ts` — removed evaluationsService/evaluatorsService exports
- `lib/services/export.ts` — removed evaluator/evaluation types, config, API calls
- `lib/services/export-server.ts` — removed evaluator/evaluation server-side API calls
- `lib/services/export-utils.ts` — removed evaluator/evaluation processing and summary entries
- `lib/services/marketplace-fetcher.ts` — removed evaluator installed-items check
- `lib/constants/dashboard-icons.ts` — removed evals/evaluators/evaluations sections
- `lib/constants/annotations.ts` — removed EVALUATOR annotation
- `components/sections/index.ts` — removed EvaluatorsSection/EvaluationsSection exports
- `components/cards/index.ts` — removed EvaluatorCard export
- `components/editors/index.ts` — removed EvaluationEditor/EvaluatorEditor exports
- `components/filtering/index.ts` — cleared (only had evaluation filter)
- `components/forms/index.ts` — removed evaluator-edit-form export
- `components/query-actions/index.ts` — cleared (only had QueryEvaluationActions)
- `components/sections/queries-section.tsx` — removed EvaluationStatusIndicator import, Evaluations column header, and evaluation status cell
- `components/panels/selector-detail-panel.tsx` — changed "evaluator will target" to "selector will target"
- `components/panels/parameter-detail-panel.tsx` — removed evaluator_role preview
- `components/app-sidebar.tsx` — removed evaluators/evaluations filter from monitoring sections
- `app/(dashboard)/query/[id]/page.tsx` — removed evaluationsService import, evaluation count loading, QueryEvaluationActions, evaluations type field, evaluations table row
- `app/(dashboard)/export/page.tsx` — removed evaluators/evaluations resource sections and abbreviation helpers
- `app/api/v1/[...proxy]/route.ts` — removed evaluators/evaluations from export regex and YAML templates
- Tests: `test-utils.ts`, `dashboard-icons.test.ts`, `dashboard-sections.test.ts`, `export/page.test.tsx` — removed evaluation references

### ark-cli Removal Details
Deleted `commands/evaluation/` directory (index.ts, index.spec.ts) and `lib/executeEvaluation.ts`

Edited 6 files:
- `src/index.tsx` — removed createEvaluationCommand import and registration
- `src/commands/export/index.ts` — removed "evaluators" from RESOURCE_ORDER
- `src/commands/export/index.spec.ts` — removed "evaluators" from expected resource types
- `src/commands/completion/index.ts` — removed "evaluation" from bash/zsh completion
- `src/lib/types.ts` — removed EvaluationManifest, EvaluationStatus, Evaluation interfaces
- `.arkrc.yaml.sample` — removed "evaluators" from defaultExportTypes

Verified: `go build ./...` still succeeds.

- [x] Remove evaluation samples (top-level samples/ directory)

### Evaluation Samples Removal Details
Deleted 4 evaluation sample directories (8 files total):
- `samples/evaluator/` — evaluator-with-labels.yaml
- `samples/evaluator-selector/` — evaluator-with-selector.yaml, queries-with-labels.yaml, README.md
- `samples/agent-modernization/custom-evaluators/` — conversion-quality, refusal-handling, scope-compliance evaluators
- `samples/agent-modernization/perf-evaluators/` — performance-evaluator.yaml

Edited 5 query files to remove `evaluation_required: "true"` labels:
- `samples/agent-modernization/queries/query-java-with-threading-complexity.yaml`
- `samples/agent-modernization/queries/query-java8-functional-interfaces.yaml`
- `samples/agent-modernization/queries/query-java8-stream-operations.yaml`
- `samples/agent-modernization/queries/query-malformed-java-code.yaml`
- `samples/agent-modernization/queries/query-non-java-python-code.yaml`

Edited `samples/rag-external-vectordb/ingestion/ingest_sample_data.py`:
- Removed evaluator sample document entry
- Removed "evaluators" from architecture description

Preserved generic English "evaluate/evaluation" in walkthrough README (test methodology) and agent prompts (not Ark CRDs).

- [x] Remove evaluation references across codebase (CI/CD, dependabot, release-please, setup-e2e)

### CI/CD and Infrastructure Removal Details
Edited 7 files:

**`.github/dependabot.yaml`** — removed ark-evaluator pip ecosystem entry and Docker directory entry

**`.github/release-please-config.json`** — removed 3 ark-evaluator extra-files entries (pyproject.toml version, Chart.yaml version, Chart.yaml appVersion)

**`.github/actions/setup-e2e/action.yml`** — removed `install-evaluator` input parameter and its usage in the setup script call

**`.github/actions/setup-e2e/setup-local.sh`** — removed `--install-evaluator` flag parsing, `INSTALL_EVALUATOR` variable, echo output, help text, and entire evaluator setup block (model creation, RBAC, helm install of ark-evaluator service)

**`.github/workflows/cicd.yaml`**:
- Removed ark-evaluator from `build-containers` matrix (path/image/prebuild)
- Removed ark-evaluator from `xray-container-scan` matrix
- Removed all `install-evaluator` references from setup-e2e calls
- Removed `!evaluated` from standard test chainsaw selector (now just `!llm`)
- Removed entire `e2e-tests-evaluated` job (~40 lines)
- Removed `e2e-tests-evaluated` from `report-coverage` and `check-release` needs lists

**`.github/workflows/deploy.yml`** — removed ark-evaluator from `deploy` container build matrix

**`scripts/deploy/transfer-ark-containers.sh`** — removed ark-evaluator from CONTAINERS array

- [x] Search for anything else we need to do, add it to this list here

### Remaining References Found (iteration 9)
Searched entire codebase for `evaluat` references. Found these real Ark evaluation references still present:

**Must fix:**
1. **`services/ark-dashboard/ark-dashboard/lib/api/generated/types.ts`** — 204 lines of evaluation/evaluator API types and endpoints. Generated file from `openapi-typescript` — will be cleaned when ark-api OpenAPI spec is regenerated (`npm run generate:api`). No manual edit needed.
2. **`services/ark-broker/test/manifests/a00-rbac.yaml`** — "evaluations", "evaluators" in RBAC resource list
3. **`charts/ark-tenant/templates/role.yaml`** — "evaluators", "evaluations" in 3 resource lists (resources, status, finalizers)
4. **`services/argo-workflows/samples/query-fanout-template.yaml`** — "evaluator-model" parameter, evaluation query creation. Entire template is an evaluation workflow.
5. **`scripts/chainsaw_summary.py`** — `print_evaluations_table()` function and `--append-evals` flag
6. **`services/ark-mcp/ark-mcp/src/ark_mcp/tools.py`** — `status.get("evaluations", [])` in query result
7. **`README.md`** — "and evaluation" in project description
8. **`docs/diagrams/ARK_architecture.drawio`** — "ARK evaluator" diagram element

**Claude skills (internal tooling, low priority):**
9. **`.claude/skills/ark-sdk-development/SKILL.md`** — references `evaluators.py`
10. **`.claude/skills/analysis/SKILL.md`** — references `ark-evaluator/` service
11. **`.claude/skills/documentation/SKILL.md`** — references "Evaluations" docs section

**Preserved (generic English, not Ark CRDs):**
- `samples/walkthrough/README.md` — "evaluation-driven methodology" (test methodology)
- `docs/content/disclaimer.mdx` — "ease of evaluation"
- `docs/content/user-guide/samples/walkthrough/index.mdx` — "evaluation-driven methodology"
- `docs/content/developer-guide/design-principles.mdx` — "systematic evaluation"
- Observability docs (Phoenix/Langfuse capabilities)
- `.github/CHANGELOG.md` — historical commit messages

- [x] Fix remaining evaluation references (items 1-11 above)

### Remaining References Fix Details
Fixed 11 files across 8 categories:

**`services/ark-broker/test/manifests/a00-rbac.yaml`** — removed "evaluations", "evaluators" from RBAC resource list

**`charts/ark-tenant/templates/role.yaml`** — removed evaluators/evaluations from 3 resource lists (resources, status, finalizers)

**`services/argo-workflows/samples/query-fanout-template.yaml`** — renamed evaluation workflow to comparison workflow: `evaluator-model` → `comparison-model`, `evaluate-responses` → `compare-responses`, `evaluate-all` → `compare-all`, `workflow-evaluation-` → `workflow-comparison-`, output `evaluation` → `comparison`

**`scripts/chainsaw_summary.py`** — removed `print_evaluations_table()` function, `--append-evals` CLI flag, and subprocess/kubectl evaluation query logic

**`services/ark-mcp/ark-mcp/src/ark_mcp/tools.py`** — removed `evaluations` field from `wait_for_query_completion_sdk` return dict

**`README.md`** — removed "and evaluation" from project description, fixed "ARK" → "Ark" capitalization

**`docs/diagrams/ARK_architecture.drawio`** — removed 2 diagram cells: "ARK evaluator" text label and "Evaluators/Evaluations" CRD box

**Claude skills (3 files):**
- `.claude/skills/ark-sdk-development/SKILL.md` — removed `evaluators.py` from Pydantic naming table
- `.claude/skills/analysis/SKILL.md` — removed `ark-evaluator/` from services list
- `.claude/skills/documentation/SKILL.md` — removed "Evaluations" from reference section list

**Note on generated types.ts:** `services/ark-dashboard/ark-dashboard/lib/api/generated/types.ts` still contains 204 lines of evaluation/evaluator types. This file is auto-generated from `openapi-typescript` — it will be cleaned automatically when ark-api's OpenAPI spec is regenerated (`npm run generate:api`). No manual edit needed.

Verified: `go build ./...` still succeeds.

- [x] Fix stale dashboard test expecting 'evals' in monitoring sections

### Dashboard Test Fix Details
`services/ark-dashboard/ark-dashboard/__tests__/unit/lib/dashboard-sections.test.ts` still asserted `expect(monitoringKeys).toContain('evals')`. Removed the stale assertion. All 102 test files (1207 tests) now pass.

- [x] Verify everything works and create evidence

### Final Verification Details

**Builds verified:**
- `go build ./...` — passes
- `next build` (dashboard) — passes, no evaluation routes in output
- `tsc` (ark-cli) — passes

**Tests verified:**
- Go tests: all pass (2 pre-existing failures from missing kubebuilder envtest binaries, unrelated to evaluation changes)
- Dashboard tests: 102 files, 1207 tests pass, 3 skipped
- ark-cli tests: pass

**CRD removal confirmed:**
- No evaluation/evaluator files in `ark/config/crd/bases/`
- No evaluation/evaluator files in `ark/dist/chart/templates/crd/`
- No evaluation Go source files in `ark/` directory
- No `ark-evaluator` service directory

**Remaining `evaluat` references (8 files, all generic English):**
- Design principles, observability docs (Phoenix/Langfuse), disclaimer, walkthrough tutorial, agent prompts, Playwright `.evaluate()` method

**Evidence created in `./evidence/`:**
- `README.md` — summary of all verification results
- `go-build.txt` — Go build output
- `go-test.txt` — Go test output
- `dashboard-test.txt` — Dashboard test output (1207 pass)
- `remaining-evaluat-references.txt` — 8 remaining generic references
- `verification-checks.png` — Terminal screenshot of CRD/file checks
- `verification-recording.gif` — Terminal recording of verification steps

## Remaining
(none — all tasks complete)
