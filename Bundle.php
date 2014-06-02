<?php
namespace Asgard\Data;

/**
 * The data bundle.
 * 
 * @author Michel Hognerud <michel@hognerud.net>
*/
class Bundle extends \Asgard\Core\BundleLoader {
	public function buildApp($app) {
		$app->register('data', function($app) { return new Data($app->get('db')); } );
	}
}