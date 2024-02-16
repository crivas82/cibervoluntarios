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
DOCKER_PATH           = .docker/
RUN_FROM_DOCKER_PATH = cd ${DOCKER_PATH} &&
RUN_DOCKER_WITH_USER = U_ID=${UID} docker exec --user ${UID}

composer-install: ## Installs composer dependencies
	${RUN_DOCKER_WITH_USER} -i ${DOCKER_CIBERVOLUNTARIOS_APP} bash -c "cd /var/www/cibervoluntarios && composer install --no-interaction"

