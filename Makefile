.DEFAULT_GOAL := help

ifeq ($(COMPOSER_HOME),)
	export COMPOSER_HOME=~/.composer
endif

# Mute all `make` specific output. Comment this out to get some debug information.
#.SILENT:

.PHONY: help
help:
	grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-12s[0m %s\n", $$1, $$2}'

.PHONY: phpunit
test: ## Runs unit tests
	vendor/bin/phpunit

.PHONY: cov
cov: ## Runs unit tests with coverage
	phpdbg -qrr vendor/bin/phpunit -c phpunit.coverage.xml \
		--log-junit .coverage/phpunit.junit.xml --coverage-xml .coverage/coverage-xml
	
.PHONY: watch
watch: ## Runs unit watcher
	vendor/bin/phpunit-watcher watch

.PHONY: metrics
metrics: ## Collect metrics
	vendor/bin/phpmetrics --report-html=./.metrics src

.PHONY: fmt
fmt: ## Apply PSR2 code style
	vendor/bin/php-cs-fixer --verbose --show-progress=dots --diff --using-cache=no fix

.PHONY: cs
cs: ## Check PSR2 code style
	vendor/bin/php-cs-fixer --verbose --show-progress=dots --diff --using-cache=no --dry-run fix

.PHONY: stan
stan: ## Run phpstan with maximum checks
	vendor/bin/phpstan analyse src --configuration phpstan.neon --level max

.PHONY: inf
inf: cov ## Throws infection into tests
	phpdbg -qrr vendor/bin/infection --threads=$$(nproc) --coverage=.coverage --show-mutations --verbose

.PHONY: bench
bench: ## Run benchmarks using phpbench
	vendor/bin/phpbench run benchmarks/
