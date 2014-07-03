<?php namespace Values;

interface TableInterface
{
	function getPrimaryKeyName();
	function getPrimaryKeyValue();
	function getName();
}