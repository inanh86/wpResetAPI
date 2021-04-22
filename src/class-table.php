<?php namespace inanh86\System;
defined('ABSPATH') || exit;

class Table extends \WP_List_Table {
    protected $data = [];
    public function __construct() {
		// Set parent defaults.
		parent::__construct( array(
			'singular' => 'movie',     // Singular name of the listed records.
			'plural'   => 'movies',    // Plural name of the listed records.
			'ajax'     => false,       // Does this table support ajax?
		) );
	}
}