<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
abstract class AbstractModel
{

	public function __construct(array $initialValues = array())
	{
		foreach ($initialValues as $attr => $value) {
			call_user_func(array($this, 'set' . ucfirst($attr)), $value);
		}
	}

}