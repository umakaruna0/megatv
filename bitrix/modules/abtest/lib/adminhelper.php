<?php

namespace Bitrix\ABTest;

class AdminHelper
{

	/**
	 * Returns script filename by URL
	 *
	 * @param string $site Site ID.
	 * @param string $url URL.
	 * @return string|null
	 */
	public static function getRealPath($site, $url)
	{
		$docRoot = rtrim(\Bitrix\Main\SiteTable::getDocumentRoot($site), '/');

		$url = str_replace('\\', '/', $url);
		$url = \CHTTP::urnEncode($url);
		$uri = new \Bitrix\Main\Web\Uri($url);

		$path = \CHTTP::urnDecode($uri->getPath());
		if (substr($path, -1, 1) == '/')
			$path .= 'index.php';

		$file = new \Bitrix\Main\IO\File($docRoot.$path);
		if ($file->isExists())
			return substr($file->getPath(), strlen($docRoot));

		if ($rewriteRules = AdminHelper::getRewriteRules($site))
		{
			$pathQuery = \CHTTP::urnDecode($uri->getPathQuery());

			foreach ($rewriteRules as &$item)
			{
				if (preg_match($item['CONDITION'], $pathQuery))
				{
					$url = empty($item['PATH']) && !empty($item['RULE'])
						? preg_replace($item['CONDITION'], $item['RULE'], $pathQuery)
						: $item['PATH'];

					$url = \CHTTP::urnEncode($url);
					$uri = new \Bitrix\Main\Web\Uri($url);

					$path = \CHTTP::urnDecode($uri->getPath());

					$file = new \Bitrix\Main\IO\File($docRoot.$path);
					if ($file->isExists())
					{
						$pathTmp  = str_replace('.', '', strtolower(ltrim($path, '/\\')));
						$pathTmp7 = substr($pathTmp, 0, 7);

						if ($pathTmp7 == 'upload/' || $pathTmp7 == 'bitrix/')
							continue;

						if ($file->getExtension() != 'php')
							continue;

						return substr($file->getPath(), strlen($docRoot));
					}
				}
			}
		}

		return null;
	}

	/**
	 * Returns urlrewrite array
	 *
	 * @param string $site Site ID.
	 * @return array
	 */
	private static function getRewriteRules($site)
	{
		$docRoot = rtrim(\Bitrix\Main\SiteTable::getDocumentRoot($site), '/');

		$rewriteRules = array();
		$arUrlRewrite =& $rewriteRules;

		$rewriteFile = new \Bitrix\Main\IO\File($docRoot.'/urlrewrite.php');
		if ($rewriteFile->isExists())
			include $rewriteFile->getPath();

		return $rewriteRules;
	}

}
