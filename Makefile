#!/usr/bin/make
# -*- makefile -*-

all :
	make -C _srcf/inc
	make -C inc

.PHONY : all
.DEFAULT_GOAL := all
