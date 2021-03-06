<?php
namespace Asgard\Data;

/**
 * The data bundle.
 *
 * @author Michel Hognerud <michel@hognerud.net>
*/
class Bundle extends \Asgard\Core\BundleLoader {
	public function buildContainer(\Asgard\Container\ContainerInterface $container) {
		$container->register('data', function($container) { return new Data($container->get('db')); } );
	}
}