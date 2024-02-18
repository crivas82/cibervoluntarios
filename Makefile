#!/bin/bash

# Include Enviroment variables
-include .env

OSFLAG 				:=
ifeq ($(OS),Windows_NT)
	OSFLAG = WINDOWS
	ifeq ($(PROCESSOR_ARCHITECTURE),AMD64)
		OSFLAG = WINDOWS
	endif
	ifeq ($(PROCESSOR_ARCHITECTURE),x86)
		OSFLAG = WINDOWS
	endif
else
	UNAME_S := $(shell uname -s)
	ifeq ($(UNAME_S),Linux)
		OSFLAG = LINUX
	endif
	ifeq ($(UNAME_S),Darwin)
		OSFLAG = MAC
	endif
		UNAME_P := $(shell uname -p)
	ifeq ($(UNAME_P),x86_64)
		OSFLAG = LINUX
	endif
	ifneq ($(filter arm%,$(UNAME_P)),)
		OSFLAG = MAC
	endif
endif

ifeq ($(OSFLAG),LINUX)
	UID = $(shell id -u) # Unix Users
endif
ifeq ($(OSFLAG),MAC)
	UID = $(shell id -u) # Unix Users
endif
ifeq ($(OSFLAG),WINDOWS)
	UID = 'root'# Windows Users
endif

DOCKER_SHARED_TOOLS = shared_tools
DOCKER_SHARED_MAILER = shared_mailer
DOCKER_SHARED_MOCKSERVER = shared_mockserver
DOCKER_CIBERVOLUNTARIOS_APP = sf6_cibervoluntarios_php

# Docker Paths
DOCKER_PATH           = ./
RUN_FROM_DOCKER_PATH = cd ${DOCKER_PATH} &&
RUN_DOCKER_WITH_USER = U_ID=${UID} docker exec --user ${UID}
RUN_DOCKER_TTY = ${RUN_DOCKER_WITH_USER} -it


init-project: ## Start the containers
	'$(MAKE)' start
	'$(MAKE)' composer-install

start: ## Start the containers
	${RUN_FROM_DOCKER_PATH} cp -n docker-compose.yml.dist docker-compose.yml || true
	docker-compose  -f docker-compose.yml -p cibervoluntarios up -d

stop: ## Stop the containers
	docker-compose -f docker-compose.yml stop

destroy: ##Remove containers
	${RUN_FROM_DOCKER_PATH} U_ID=${UID} docker-compose -f docker-compose.yml down
	${RUN_FROM_DOCKER_PATH} rm -f docker-compose.yml || true

restart: ## Restart the containers
	'$(MAKE)' stop && '$(MAKE)' start

build: ## Rebuild all the containers
	${RUN_FROM_DOCKER_PATH} cp -n docker-compose.yml.dist docker-compose.yml || true
	${RUN_FROM_DOCKER_PATH} U_ID=${UID} docker-compose -f docker-compose.yml build

composer-install: ## Install composer dependencies
	${RUN_DOCKER_WITH_USER} -i ${DOCKER_CIBERVOLUNTARIOS_APP} bash -c "cd /var/www/cibervoluntarios && composer install --no-interaction"

update-database-schema: ## Update database schema
	${RUN_DOCKER_WITH_USER} -i ${DOCKER_CIBERVOLUNTARIOS_APP} bash -c "cd /var/www/cibervoluntarios && php bin/console doctrine:schema:update --force --no-interaction"

load-fixtures-data: ## Load fixtures data
	${RUN_DOCKER_WITH_USER} -i ${DOCKER_CIBERVOLUNTARIOS_APP} bash -c "cd /var/www/cibervoluntarios && php bin/console doctrine:fixtures:load --no-interaction"

cache-clear: ## Clear symfony cache
	${RUN_DOCKER_TTY} ${DOCKER_CIBERVOLUNTARIOS_APP} php bin/console cache:clear

run-tests: ## Run Unit Tests
	${RUN_DOCKER_TTY} ${DOCKER_CIBERVOLUNTARIOS_APP} bash -c "cd /var/www/cibervoluntarios && vendor/bin/phpunit --testsuite cibervoluntarios_pizza_tests"

