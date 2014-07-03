<?php namespace Values;

interface EntityInterface
{
	function getPrimaryKeyName();
	function getPrimaryKeyValue();
	function getName();
}