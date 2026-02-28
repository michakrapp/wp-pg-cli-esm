<?php
/**
 * Plugin Name: Test ESM
 * Version: 0.1.0
 */

function test_esm_register_block() {
    register_block_type( __DIR__ );
}
add_action( 'init', 'test_esm_register_block' );
